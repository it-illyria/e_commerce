<?php

namespace App\Service;

use App\Model\Order;

class OrderService
{
    private Order $orderModel;

    public function __construct()
    {
        $this->orderModel = new Order();
    }

    // Add product to the cart (simplified)
    public function addToCart($productId)
    {
        $_SESSION['cart'][] = $productId;
    }

    // Calculate cart total
    public function getCartTotal()
    {
        // Logic to calculate total from cart (simplified)
        return 100; // Simplified total
    }

    // Create an order
    public function createOrder($userId, $address, $total, $phone): bool
    {
        $orderModel = new Order();
        return $orderModel->createOrder($userId, $address, $total, $phone);
    }

    public function getCartItems()
    {
        return $_SESSION['cart'] ?? [];
    }


}
