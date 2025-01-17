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
        $showError = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($this->authService->loginUser($username, $password)) {
                // Decode the refurl before redirecting
                $refUrl = isset($_POST['refurl']) ? base64_decode($_POST['refurl']) : '/';

                // Ensure the refUrl is a valid path
                if (!filter_var($refUrl, FILTER_VALIDATE_URL)) {
                    $refUrl = '/'; // Default to home if the URL is invalid
                }

                header("Location: $refUrl");
                exit();
            } else {
                $showError = "Invalid credentials!";
            }
        }

        include __DIR__ . "/../views/login.php";
    }



    public function logout()
    {
        $this->authService->logout();
        header("Location: /user/login");
        exit();
    }

    public function viewProfile($userId)
    {
        if (!$userId) {
            header("Location: /user/login");
            exit();
        }

        try {
            $user = $this->authService->getUserById($userId);

            if ($user) {
                $_SESSION['profile_data'] = $user;
                include __DIR__ . '/../views/profile.php';
            } else {
                http_response_code(404);
                include __DIR__ . '/../views/404.php';
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            error_log("Error fetching user profile: " . $e->getMessage());

            // Display a generic error message to the user
            http_response_code(500);
            echo "An error occurred while loading your profile.";
            exit();
        }
    }

}
