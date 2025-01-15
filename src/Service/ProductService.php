<?php

namespace App\Service;


use App\Model\Product;

class ProductService
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    // Get all products
    public function getAllProducts(): array
    {
        return $this->productModel->getAllProducts();
    }

    // Add a new product
//    public function addProduct($name, $description, $price)
//    {
//        $this->productModel->createProduct($name, $description, $price);
//    }
    public function searchProducts($search): array
    {
        return $this->productModel->searchProducts($search);
    }

    // Get all products for pagination
    public function getProductsByPage(int $page, int $limit = 15): array
    {
        return $this->productModel->getProductsByPage($page, $limit);
    }

    // Get the total number of products
    public function getTotalProductCount(): int
    {
        return $this->productModel->getTotalProductCount();
    }

    public function getProductById(int $productId): ?array
    {
        return $this->productModel->getProductById($productId);
    }


}
