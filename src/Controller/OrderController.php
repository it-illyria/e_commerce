<?php

namespace App\Controller;

use App\Service\OrderService;

class OrderController
{
    private OrderService $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    // Add product to cart
    public function addToCart($productId)
    {
        $this->orderService->addToCart($productId);
        echo "Product added to cart!";
    }

    // Checkout process
    public function checkout() {
        $total = $this->orderService->getCartTotal();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $userId = $_SESSION['user_id'] ?? null; // Null for guests
            $this->orderService->createOrder($userId, $address, $total, $phone);
            echo "Order placed successfully!";
        }
        include __DIR__ . "/../views/checkout.php";
    }

}
