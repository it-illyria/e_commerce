<?php

namespace App\Model;

use App\Database\Database;

class User
{
    private ?\mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    // Get user by username
    public function getUserByUsername($username)
    {
        $query = "SELECT id, username, email, password FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    // Register a new user
    public function createUser($username, $email, $password): bool
    {
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        return $stmt->execute();
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT id, username, email, password FROM users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getUserById($userId)
    {
        // Sanitize and prepare the query
        $query = "SELECT id, username, email, created_at FROM users WHERE id = ?";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            die('Prepare failed: ' . $this->db->error);
        }

        $stmt->bind_param('i', $userId);

        if (!$stmt->execute()) {
            die('Execution failed: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();

        return $user;
    }


}
