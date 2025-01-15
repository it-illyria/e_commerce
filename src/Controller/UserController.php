<?php

namespace App\Controller;

use App\Service\AuthenticationService;

class UserController
{
    private AuthenticationService $authService;

    public function __construct()
    {
        $this->authService = new AuthenticationService();
    }

    public function register()
    {
        $showError = null; // Initialize the error variable

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Check if password and confirm password match
            if ($password !== $confirmPassword) {
                $showError = "Passwords do not match!";
            } else {
                try {
                    // Register the user
                    $this->authService->registerUser($username, $email, $password);
                    // Redirect to login page after successful registration
                    header('Location: /user/login');
                    exit();
                } catch (\Exception $e) {
                    // Catch any exceptions thrown (e.g., username or email already exists)
                    $showError = $e->getMessage();
                }
            }
        }
        include __DIR__ . "/../views/register.php";
    }

    // User login
    public function login()
    {
        $showError = null; // Initialize the error variable

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Try to log the user in
            if ($this->authService->loginUser($username, $password)) {
                // Redirect to the original page (or default page if refurl is not set)
                $refUrl = $_POST['refurl'] ?? '/';
                header("Location: $refUrl");
                exit();
            } else {
                // If login fails, set the error message
                $showError = "Invalid credentials!";
            }
        }

        // Include the login page, passing the error message if any
        include __DIR__ . "/../views/login.php";
    }


    public function logout()
    {
        $this->authService->logout();
        header("Location: /user/login");
        exit();
    }
}
