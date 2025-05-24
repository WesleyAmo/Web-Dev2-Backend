<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\User;
use Models\Role;

class UserRepository extends Repository
{
    public function checkPassword($email, $password)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, username, hashed_password, email, full_name, role_id, phone FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row) {
                return null;  // Return false if the user doesn't exist
            }
            $user = $this->rowToUser($row);

            $result = $this->verifyPassword($password, $user->hashedPassword);
            if (!$result)
                return null;

            $user->hashedPassword = "";

            return $user;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
            return null;
        }
    }
    public function getUserById(int $userId): ?User
    {
        try {
            $stmt = $this->connection->prepare(
                'SELECT id, username, hashed_password, email, full_name, role_id, phone, refresh_token FROM users WHERE id = :userId LIMIT 1'
            );
            $stmt->execute([
                ':userId' => $userId,
            ]);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
            $user = $this->rowToUser($row);
            return $user;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }

    public function getUserByEmail(string $email): ?User
    {
        try {
            $stmt = $this->connection->prepare(
                'SELECT id, username, hashed_password, email, full_name, role_id, phone FROM users WHERE email = :email LIMIT 1'
            );
            $stmt->execute([
                ':email' => $email,
            ]);

            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $user = $this->rowToUser($row);
            return $user;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }

    public function create(User $user): ?User
    {
        try {
            $stmt = $this->connection->prepare(
                'INSERT INTO users (username, hashed_password, email, full_name, role_id, phone)
            VALUES (:username, :hashedPassword, :email, :fullName, :roleId, :phone)'
            );

            $stmt->bindParam(':username', $user->username);
            $stmt->bindParam(':hashedPassword', $user->hashedPassword);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':fullName', $user->fullName);
            $roleValue = $user->role->value;
            $stmt->bindParam(':roleId', $roleValue);
            $stmt->bindParam(':phone', $user->phone);

            $stmt->execute();

            $user->id = $this->connection->lastInsertId();

            return $user;

        } catch (PDOException $e) {
            error_log('Insert order failed: ' . $e->getMessage());
            return null;
        }
    }
    function rowToUser($row)
    {
        $user = new User();
        $user->id = $row['id'];
        $user->username = $row['username'];
        $user->hashedPassword = $row['hashed_password'];
        $user->fullName = $row['full_name'];
        $user->email = $row['email'];
        $user->role = Role::from($row['role_id']);

        $user->refresh_token = $row['refresh_token'] ?? null;

        $user->phone = $row['phone'];
        return $user;
    }

    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }

    public function updateUser(int $userId, array $updatedFields): bool
    {
        try {
            $setClauses = [];
            $params = [':userId' => $userId];

            foreach ($updatedFields as $field => $value) {
                $setClauses[] = "$field = :$field";
                $params[":$field"] = $value;
            }

            $sql = 'UPDATE users SET ' . implode(', ', $setClauses) . ' WHERE id = :userId';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);


            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
            return false;
        }
    }

    public function updatePassword($newHashedPassword, $userId)
    {
        try {
            $stmt = $this->connection->prepare(
                'UPDATE users SET hashed_password = :newPassword WHERE id = :userId'
            );
            $stmt->execute([
                ':newPassword' => $newHashedPassword,
                ':userId' => $userId,
            ]);
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }

    public function storeRefreshToken($userId, $refreshToken)
    {
        try {
            $stmt = $this->connection->prepare(
                'UPDATE users SET refresh_token = :refreshToken WHERE id = :userId'
            );
            $stmt->execute([
                ':refreshToken' => $refreshToken,
                ':userId' => $userId,
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Failed: ' . $e->getMessage());
        }
    }

}

