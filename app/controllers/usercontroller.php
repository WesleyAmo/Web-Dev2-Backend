<?php

namespace Controllers;

use Exception;
use Services\UserService;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Models\Role;

class UserController extends Controller
{
    private $service;
    private $secretKey = "your_secret_key";

    function __construct()
    {
        $this->service = new UserService();
    }

    public function login()
    {
        try {
            $postedUser = $this->createObjectFromPostedJson("Models\\User");
            $user = $this->service->checkPassword($postedUser->email, $postedUser->password);

            if (!$user) {
                $this->respondWithError(401, "Invalid login, please try again");
                return;
            }

            $tokens = $this->generateJwt($user);

            $hashedRefreshToken = password_hash($tokens['refreshToken'], PASSWORD_BCRYPT);
            $this->service->storeRefreshToken($user->id, $hashedRefreshToken);

            $this->respond([
                'accessToken' => $tokens['accessToken'],
                'refreshToken' => $tokens['refreshToken'],
                'user' => $user
            ]);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $postedUser = $this->createObjectFromPostedJson("Models\\User");
            $validationResult = $this->validateUser($postedUser);
            if ($validationResult !== true) {
                $this->respondWithError(400, $validationResult);
                return;
            }

            // Check if email already exists
            if ($this->service->getUserByEmail($postedUser->email)) {
                $this->respondWithError(409, "Email already exists");
                return;
            }
            $postedUser->hashedPassword = password_hash($postedUser->password, PASSWORD_BCRYPT);

            // Assign a default role 
            if (!$postedUser->role) {
                $postedUser->role = Role::Customer;
            }

            $createdUser = $this->service->create($postedUser);
            if ($createdUser) {
                $this->respond([
                    'message' => 'User successfully registered',
                    'userId' => $createdUser->id
                ]);
            }

        } catch (Exception $e) {
            $this->respondWithError(500, "An error occurred during registration: " . $e->getMessage());
        }
    }

    public function refreshToken()
    {
        $data = $this->createObjectFromPostedJson("Models\\User");
        if (empty($data->refresh_token)) {
            $this->respondWithError(400, "Refresh token is required");
            return;
        }

        try {
            // Verify the refresh token
            $decoded = JWT::decode($data->refresh_token, new Key($this->secretKey, 'HS256'));
            $userId = $decoded->sub;

            // Get user and verify stored refresh token
            $user = $this->service->getOne($userId);
            if (!$user || !password_verify($data->refresh_token, $user->refresh_token)) {
                $this->respondWithError(403, "Invalid refresh token");
                return;
            }

            // Generate new tokens (same as login)
            $tokens = $this->generateJwt($user);

            $hashedRefreshToken = password_hash($tokens['refreshToken'], PASSWORD_BCRYPT);
            $this->service->storeRefreshToken($userId, $hashedRefreshToken);

            $this->respond([
                'accessToken' => $tokens['accessToken'],
                'refreshToken' => $tokens['refreshToken']
            ]);

        } catch (Exception $e) {
            $this->respondWithError(401, "Invalid refresh token: " . $e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            $decodedToken = $this->checkAuthorization(null, $id);
            if (!$decodedToken) {
                return;
            }

            $updatedFields = $this->createObjectFromPostedJson(null, true);

            // Validate specific fields
            if (isset($updatedFields['phone'])) {
                if (!preg_match('/^\\d{10,15}$/', $updatedFields['phone'])) {
                    $this->respondWithError(400, 'Invalid phone number format');
                    return;
                }
            }

            if (isset($updatedFields['email'])) {
                if (!filter_var($updatedFields['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->respondWithError(400, 'Invalid email address');
                    return;
                }
                $existingUser = $this->service->getUserByEmail($updatedFields['email']);
                if ($existingUser && $existingUser->id !== $id) {
                    $this->respondWithError(409, "Email already exists");
                    return;
                }
            }

            $success = $this->service->updateUser($id, $updatedFields);

            if ($success) {
                $this->respond(['message' => 'User updated successfully']);
            } else {
                $this->respondWithError(400, 'Failed to update user');
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function updatePassword($userId)
    {
        try {
            $decodedToken = $this->checkAuthorization(null, $userId);
            if (!$decodedToken) {
                return;
            }
            $data = $this->createObjectFromPostedJson(null, true);

            if (!isset($data['newPassword'])) {
                $this->respondWithError(400, 'New password is required');
                return;
            }
            if (strlen($data['newPassword']) < 8 || !preg_match('/\d/', $data['newPassword'])) {
                $this->respondWithError(400, 'Password must be at least 8 characters long and contain a number.');
                return;
            }

            // Check if the old password is correct 
            if (isset($data['oldPassword'])) {
                $user = $this->service->getOne($userId);
                if (!$user || !password_verify($data['oldPassword'], $user->hashedPassword)) {
                    $this->respondWithError(400, 'Old password is incorrect');
                    return;
                }
            }

            // Hash the new password
            $newHashedPassword = password_hash($data['newPassword'], PASSWORD_BCRYPT);
            $this->service->updatePassword($newHashedPassword, $userId);

            $this->respond(['message' => 'Password updated successfully']);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }


    function getOne($id)
    {
        try {
            $user = $this->service->getOne($id);
            if (!$user) {
                $this->respondWithError(404, "Product not found");
                return;
            }
            $this->respond($user);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    private function generateJwt($user)
    {
        // Access token (short-lived)
        $accessTokenPayload = [
            'sub' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'iat' => time(),
            'exp' => time() + (60 * 30) // 30 minutes expiry
        ];

        // Refresh token (long-lived)
        $refreshTokenPayload = [
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 90) // 90 days expiry
        ];

        return [
            'accessToken' => JWT::encode($accessTokenPayload, $this->secretKey, 'HS256'),
            'refreshToken' => JWT::encode($refreshTokenPayload, $this->secretKey, 'HS256')
        ];
    }

    private function validateUser($postedUser)
    {
        if (empty($postedUser->fullName) || empty($postedUser->username) || empty($postedUser->email) || empty($postedUser->phone) || empty($postedUser->password)) {
            return "All fields are required.";
        }

        // Validate email format
        if (!filter_var($postedUser->email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }

        // Validate password length
        if (strlen($postedUser->password) < 8) {
            return "Password must be at least 8 characters long.";
        }

        // Validate phone number format
        $phonePattern = '/^[0-9]{10}$/';
        if (!preg_match($phonePattern, $postedUser->phone)) {
            return "Invalid phone number. It should be 10 digits.";
        }

        return true; // If all validations pass
    }

    public function getMe()
    {
        try {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            if (empty($authHeader)) {
                $this->respondWithError(401, 'Authorization header missing');
                return;
            }

            if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $this->respondWithError(401, 'Authorization header format should be: Bearer [token]');
                return;
            }

            $jwt = $matches[1];
            if (empty($jwt)) {
                $this->respondWithError(401, 'JWT token missing');
                return;
            }
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            $user = $this->service->getOne($decoded->sub);

            if (!$user) {
                $this->respondWithError(404, 'User not found');
                return;
            }

            //  Return user data (excluding sensitive information)
            $this->respond([
                'id' => $user->id,
                'username' => $user->username,
                'fullName' => $user->fullName,
                'role' => $user->role,
                'email' => $user->email,
                'phone' => $user->phone
            ]);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

}
