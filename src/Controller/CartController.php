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

        if ($userId) {
            $cartItems = $this->cartService->getCartItems($userId);
            if (empty($cartItems) && isset($_SESSION['cart'])) {
                $this->cartService->mergeGuestCartWithUserCart($userId);
                $cartItems = $this->cartService->getCartItems($userId);
            }
        } else {$cartItems = $this->cartService->getCartItems();
        }

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

    // Update Cart Quantity
    public function updateQuantity()
    {
        $productId = $_POST['productId'];
        $quantity = $_POST['quantity'];

        if ($_SESSION['user_id']) {
            // Update for logged-in user
            $this->cartService->updateProductQuantity($productId, $_SESSION['user_id'], $quantity);
        } else {
            // Update for guest user
            $this->cartService->updateProductQuantity($productId, null, $quantity);
        }

        $cartTotal = $this->cartService->calculateTotal($_SESSION['user_id'] ?? null);

        echo json_encode([
            'cartUpdated' => true,
            'cartTotal' => number_format($cartTotal, 2)
        ]);
    }

    // Remove from Cart
    public function removeFromCart()
    {
        $productId = $_POST['productId'];

        if ($_SESSION['user_id']) {
            // Remove for logged-in user
            $this->cartService->removeFromCart($productId, $_SESSION['user_id']);
        } else {
            // Remove for guest user
            $this->cartService->removeFromCart($productId);
        }

        echo json_encode(['removed' => true]);
    }
}