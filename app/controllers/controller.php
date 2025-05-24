<?php

namespace Controllers;

use Exception;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Models\Role;

class Controller
{
    function respond($data)
    {
        $this->respondWithCode(200, $data);
    }

    function respondWithError($httpcode, $message)
    {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    function createObjectFromPostedJson($className = null, $asArray = false)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, $asArray);

        if ($asArray) {
            return $data;
        }

        if ($className) {
            $object = new $className();
            foreach ($data as $key => $value) {
                if (is_object($value)) {
                    continue;
                }
                if ($key === 'role' && property_exists($object, 'role')) {
                    $object->role = Role::from($value);
                } else {
                    $object->{$key} = $value;
                }
            }


            if (property_exists($object, 'role') && empty($object->role)) {
                $object->role = Role::Customer;
            }

            return $object;
        }

        return null;
    }

    protected function checkAuthorization($requiredRole = null, $userId = null)
    {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $this->respondWithError(401, "No token provided");
            return false;
        }

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $arr = explode(" ", $authHeader);
        $jwt = $arr[1]; // Get the token part

        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, new Key("your_secret_key", 'HS256'));

                if ($requiredRole !== null && $decoded->role !== $requiredRole) {
                    // If the user doesn't have the required role, deny access
                    $this->respondWithError(403, "You do not have permission to access this resource.");
                    return false;
                }

                // If a userId is provided, ensure the logged-in user matches or is an admin
                if ($userId !== null && $decoded->role !== 1 && $decoded->sub != $userId) {
                    $this->respondWithError(403, "Access denied");
                    return false;
                }

                return $decoded; // Return decoded JWT if authorized
            } catch (Exception $e) {
                $this->respondWithError(401, $e->getMessage());
                return false;
            }
        }
        return false;
    }


}
