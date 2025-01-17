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

        $this->userModel->createUser($username, $email, $password);
    }

    public function loginUser($username, $password): bool
    {
        // Get the user by username
        $user = $this->userModel->getUserByUsername($username);

        if (!$user) {
            error_log("No user found with username: $username");
            return false;
        }

        // Log the retrieved user details
        error_log("User Found: " . print_r($user, true));
        error_log("Stored Hash: " . $user['password']);

        // Verify the password using PASSWORD_VERIFY
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            error_log("Login successful for username: $username");
            return true;
        }

        error_log("Password verification failed for username: $username");
        return false;
    }


    public function logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
    }

    public function getUserById($userId)
    {
        return $this->userModel->getUserById($userId);
    }
}