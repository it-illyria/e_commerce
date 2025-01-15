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

    public function getProductDetailsById($productId)
    {
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
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

    // Calculate the total price for a user or guest
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
            if ($product['stock'] < 10) {
                $price *= 1.1;  // Apply 10% price increase for low stock
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
            if ($item['stock'] < 10) {
                $price *= 1.1;  // Apply 10% price increase for low stock
            }

            $total += $price * $item['quantity'];
        }

        return $total;
    }

    // Update product quantity in cart
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
        // Check if the session cart already exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // If the product already exists in the cart, update its quantity
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            // If the product is not in the cart, add it with quantity 1
            $_SESSION['cart'][$productId] = [
                'quantity' => 1
            ];
        }

        return true;
    }

}
