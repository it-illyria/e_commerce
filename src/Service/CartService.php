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
}
