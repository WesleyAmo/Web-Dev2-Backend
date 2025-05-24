<?php
namespace Models;

class User
{
    public int $id;
    public string $username;
    public string $password;
    public string $hashedPassword;
    public string $email;
    public string $fullName;
    public Role $role;
    public string $phone;
    public ?string $refresh_token;

    public function getRoleId()
    {
        return $this->role->value;
    }
}

enum Role: int
{
    case Customer = 0;
    case Admin = 1;
}
