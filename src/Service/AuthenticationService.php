<?php

namespace App\Service;

use App\Model\User;

class AuthenticationService
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function registerUser($username, $email, $password)
    {
        // Check if username or email already exists
        if ($this->userModel->getUserByUsername($username) || $this->userModel->getUserByEmail($email)) {
            throw new \Exception("Username or email already exists.");
        }

        // Hash the password using PASSWORD_BCRYPT
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->userModel->createUser($username, $email, $hashedPassword);
    }

    public function loginUser($username, $password): bool
    {
        // Get the user by username
        $user = $this->userModel->getUserByUsername($username);

        if (!$user) {
            error_log("No user found with username: $username");
            return false;
        }

        // Verify the password using PASSWORD_BCRYPT
        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            return true;
        }

        return false;
    }

    public function logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
    }
}