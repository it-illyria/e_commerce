<?php

namespace App\Controller;

use App\Service\OrderService;
use App\Model\Product;

class OrderController
{
    private OrderService $orderService;
    private Product $productModel;

    public function __construct()
    {
        $this->orderService = new OrderService();
        $this->productModel = new Product();
    }

    // Add product to cart
    public function addToCart($productId)
    {
        $this->orderService->addToCart($productId);
        echo "Product added to cart!";
    }

    /// Checkout process
    public function checkout() {
        // Fetch the cart items
        $cartItems = $this->orderService->getCartItems();

        // Get product details for each item in the cart
        $cartProducts = [];
        foreach ($cartItems as $productId) {
            $product = $this->productModel->getProductById($productId);
            if ($product) {
                $cartProducts[] = $product;
            }
        }

        // Calculate the total from the cart items
        $total = 0;
        foreach ($cartProducts as $product) {
            $total += $product['price'];
        }

        $orderSuccess = false; // Flag to indicate order placement status

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $userId = $_SESSION['user_id'] ?? null; // Null for guests
            $this->orderService->createOrder($userId, $address, $total, $phone);
            $orderSuccess = true; // Mark order as placed
        }

        // Pass variables to the view
        include __DIR__ . "/../views/checkout.php";
    }



}