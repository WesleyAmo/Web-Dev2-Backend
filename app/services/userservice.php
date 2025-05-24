<?php
namespace Services;

use Repositories\TestRepository;
use Repositories\UserRepository;

class UserService
{

    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function getOne($id)
    {
        return $this->repository->getUserById($id);
    }

    public function checkPassword($email, $password)
    {
        return $this->repository->checkPassword($email, $password);
    }

    public function create($user)
    {
        return $this->repository->create($user);
    }

    public function getUserByEmail($email)
    {
        return $this->repository->getUserByEmail($email);
    }

    public function updateUser($userId, $updatedFields)
    {
        return $this->repository->updateUser($userId, $updatedFields);
    }
    public function updatePassword($newHashedPassword, $userId)
    {
        return $this->repository->updatePassword($newHashedPassword, $userId);
    }

    public function storeRefreshToken($userId, $hashedToken)
    {
        return $this->repository->storeRefreshToken($userId, $hashedToken);
    }
}

