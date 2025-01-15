<?php

namespace App\Model;

use App\Database\Database;
use mysqli;

class Product
{
    private ?mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    // Get all products
    public function getAllProducts(): array
    {
        $query = "SELECT * FROM products";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function searchProducts($search): array
    {
        $query = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
        $stmt = $this->db->prepare($query);
        $searchTerm = "%" . $search . "%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get products for a specific page
    public function getProductsByPage(int $page, int $limit = 15): array
    {
        $offset = ($page - 1) * $limit;
        $query = "SELECT * FROM products LIMIT $limit OFFSET $offset";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get total number of products
    public function getTotalProductCount(): int
    {
        $query = "SELECT COUNT(*) AS count FROM products";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return (int)$row['count'];
    }

    public function getProductById($productId)
    {
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        // If product exists, return its details
        return $result->fetch_assoc();
    }

}
