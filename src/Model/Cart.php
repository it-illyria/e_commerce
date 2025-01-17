<?php

namespace App\Model;

use App\Database\Database;
use mysqli;

class Cart
{
    private ?mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function addToCart($productId, $userId = null): bool
    {
        if ($userId) {
            return $this->addProductForLoggedUser($productId, $userId);
        } else {
            return $this->addProductForGuest($productId);
        }
    }

    public function removeFromCart($productId, $userId = null): bool
    {
        if ($userId) {
            return $this->removeProductForLoggedUser($productId, $userId);
        } else {
            return $this->removeProductForGuest($productId);
        }
    }

    public function getCartItems($userId = null): array
    {
        if ($userId) {
            return $this->getCartItemsForLoggedUser($userId);
        } else {
            return $this->getCartItemsForGuest();
        }
    }

    private function getCartItemsForGuest(): array
    {
        if (!isset($_SESSION['cart'])) {
            return [];
        }

        $cartItems = [];

        foreach ($_SESSION['cart'] as $productId => $item) {
            $query = "SELECT name, price, image_path, stock FROM products WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if ($product) {
                $cartItems[] = array_merge($product, ['quantity' => $item['quantity']]);
            }
        }

        return $cartItems;
    }

    private function getCartItemsForLoggedUser($userId): array
    {
        $query = "SELECT ci.*, p.name, p.price, p.image_path, p.stock
                  FROM cart_items ci
                  JOIN products p ON ci.product_id = p.id
                  WHERE ci.user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function calculateTotal($userId = null): float
    {
        if ($userId) {
            return $this->calculateTotalForLoggedUser($userId);
        } else {
            return $this->calculateTotalForGuest();
        }
    }

    private function calculateTotalForGuest(): float
    {
        $total = 0;
        foreach ($_SESSION['cart'] ?? [] as $productId => $item) {
            $query = "SELECT price, stock FROM products WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            $price = $product['price'];

            // Price increase logic for low stock
            if ($product['stock'] < 10) {
                $price *= 1.1;
            }

            $total += $price * $item['quantity'];
        }

        return $total;
    }

    private function calculateTotalForLoggedUser($userId): float
    {
        $total = 0;
        $cartItems = $this->getCartItemsForLoggedUser($userId);

        foreach ($cartItems as $item) {
            $price = $item['price'];

            // Price increase logic for low stock
            if ($item['stock'] < 10) {
                $price *= 1.1;
            }

            $total += $price * $item['quantity'];
        }

        return $total;
    }

    public function updateProductQuantity($productId, $userId, $quantity): bool
    {
        if ($userId) {
            $query = "UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iii", $quantity, $userId, $productId);
            return $stmt->execute();
        } else {
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] = $quantity;
                return true;
            }
        }

        return false;
    }

    // Add the product to the session for guest users
    private function addProductForGuest($productId): bool
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = ['quantity' => 1];
        }

        return true;
    }

    private function addProductForLoggedUser($productId, $userId): bool
    {
        $query = "INSERT INTO cart_items (user_id, product_id, quantity)
                  VALUES (?, ?, 1)
                  ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $userId, $productId);
        return $stmt->execute();
    }

    private function removeProductForGuest($productId): bool
    {
        unset($_SESSION['cart'][$productId]);
        return true;
    }

    private function removeProductForLoggedUser($productId, $userId): bool
    {
        $query = "DELETE FROM cart_items WHERE user_id = ? AND product_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $userId, $productId);
        return $stmt->execute();
    }

    public function addToCartForUser($productId, $userId, $quantity = 1): bool
    {
        $query = "INSERT INTO cart_items (user_id, product_id, quantity) 
              VALUES (?, ?, ?) 
              ON DUPLICATE KEY UPDATE quantity = quantity + ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iiii", $userId, $productId, $quantity, $quantity);
        return $stmt->execute();
    }

    public function getProductDetailsById($productId)
    {
        $query = "SELECT id, name, price, image_path, stock FROM products WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

}

