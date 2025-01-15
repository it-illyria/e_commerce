<?php

namespace App\Controller;


use App\Service\ProductService;

class ProductController
{

    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    // Show all products
    public function list(int $page = 1)
    {
        $productsPerPage = 15;
        $products = $this->productService->getProductsByPage($page, $productsPerPage);
        $totalProducts = $this->productService->getTotalProductCount();
        $totalPages = ceil($totalProducts / $productsPerPage);

        // Pass products, page number, and total pages to the view
        include __DIR__ . "/../views/products_list.php";
    }

    public function search()
    {
        $search = $_GET['search'] ?? '';
        $products = $this->productService->searchProducts($search);
        include __DIR__ . "/../views/products_list.php";
    }

    public function addToCart(int $productId)
    {
        $product = $this->productService->getProductById($productId);

        if (!$product) {
            header("Location: /product/list");
            exit();
        }

        // Initialize cart session if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add product to cart
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = $product;
            $_SESSION['cart'][$productId]['quantity'] = 1;
        } else {
            $_SESSION['cart'][$productId]['quantity']++;
        }

        // Redirect to products list
        header("Location: /product/list");
        exit();
    }

    // Show Cart
    public function showCart()
    {
        $cart = $_SESSION['cart'] ?? [];
        $totalPrice = array_reduce($cart, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);

        include __DIR__ . '/../views/cart.php';
    }


}
