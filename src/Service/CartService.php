<?php

namespace App\Service;

use App\Model\Cart;

class CartService
{
    private Cart $cartModel;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $this->cartModel = new Cart();
    }

    public function getProductDetailsById($productId)
    {
        return $this->cartModel->getProductDetailsById($productId);
    }

    public function getCartItems($userId = null): array
    {
        if ($userId) {
            return $this->cartModel->getCartItems($userId);
        } else {
            return $this->cartModel->getCartItems();
        }
    }

    public function calculateTotal($userId = null): float
    {
        if ($userId) {
            return $this->cartModel->calculateTotal($userId);
        } else {
            return $this->cartModel->calculateTotal();
        }
    }

    public function addToCart($productId, $userId = null): bool
    {
        return $this->cartModel->addToCart($productId, $userId);
    }

    public function removeFromCart($productId, $userId = null): bool
    {
        return $this->cartModel->removeFromCart($productId, $userId);
    }

    public function updateProductQuantity($productId, $userId, $quantity): bool
    {
        return $this->cartModel->updateProductQuantity($productId, $userId, $quantity);
    }

    public function mergeGuestCartWithUserCart($userId)
    {
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productId => $item) {
                $this->cartModel->addToCartForUser($productId, $userId, $item['quantity']);
            }

            // Clear the guest cart session
            unset($_SESSION['cart']);
        }
    }


}
