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
}
