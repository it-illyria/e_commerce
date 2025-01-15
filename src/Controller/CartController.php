<?php

namespace App\Controller;

use App\Service\CartService;

class CartController
{
    private CartService $cartService;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $this->cartService = new CartService();
    }

    public function viewCart($userId = null)
    {
        // If the user is not logged in, the cart items will be fetched from the session for a guest.
        if ($userId) {
            // Fetch cart items for logged-in users
            $cartItems = $this->cartService->getCartItems($userId);
        } else {
            // Fetch cart items for guest users from the session
            $cartItems = $this->cartService->getCartItems();
        }

        // Fetch the product details for each cart item
        foreach ($cartItems as &$item) {
            if (isset($item['product_id'])) {
                $productDetails = $this->cartService->getProductDetailsById($item['product_id']);
                if ($productDetails) {
                    $item['product_name'] = $productDetails['name'];
                    $item['product_price'] = $productDetails['price'];
                } else {
                    $item['product_name'] = 'Product not found';
                    $item['product_price'] = 0;
                }
            } else {
                $item['product_name'] = 'Product not found';
                $item['product_price'] = 0;
            }
        }

        $cartTotal = $this->cartService->calculateTotal($userId);

        // Pass the cart items and total to the view
        include __DIR__ . '/../views/cart.php';
    }


    public function addToCart($productId, $userId = null)
    {
        // If the user is logged in
        if ($userId) {
            $this->cartService->addToCart($productId, $userId);
        } else {
            // For guest users, add product to the session-based cart
            $this->cartService->addToCart($productId);
        }

        // Redirect to the cart page
        header('Location: /cart');
        exit;
    }

    // Remove from cart (supports both logged-in users and guests)
    public function removeFromCart($productId, $userId = null)
    {
        if ($userId) {
            $this->cartService->removeFromCart($productId, $userId);
        } else {
            $this->cartService->removeFromCart($productId);
        }

        header("Location: /cart");
        exit;
    }
}
