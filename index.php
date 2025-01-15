<?php

require_once __DIR__ . '/vendor/autoload.php';

session_start();
$userId = $_SESSION['user_id'] ?? null;

use App\Controller\OrderController;
use App\Controller\ProductController;
use App\Controller\UserController;
use App\Controller\CartController;

$requestUri = $_SERVER['REQUEST_URI'];
$uri = parse_url($requestUri, PHP_URL_PATH);

// Default values
$pageTitle = 'E-Commerce';

// Define basic routing
switch ($uri) {
    // Register route
    case '/user/register':
        $pageTitle = 'Register';
        $content = __DIR__ . '/views/register.php';
        (new UserController())->register();
        break;

    // Login route
    case '/user/login':
        $pageTitle = 'Login';
        $content = __DIR__ . '/views/login.php';
        (new UserController())->login();
        break;

    // Add to Cart route
    case '/cart/addToCart':
        $productId = $_POST['id'] ?? null;  // Expect product ID in the POST data
        if ($productId) {
            (new CartController())->addToCart($productId);
            header('Location: /cart');
            exit;
        } else {
            http_response_code(400); // Bad Request
            echo "Product ID is missing.";
        }
        break;

    // View Cart route
    case '/cart':
        $pageTitle = 'Cart';
        $content = __DIR__ . '/views/cart.php';
        (new CartController())->viewCart($userId);
        break;

    // Checkout route
    case '/checkout':
        $pageTitle = 'Checkout';
        $content = __DIR__ . '/views/checkout.php';
        (new OrderController())->checkout();
        break;

    case '/thank_you':
        $pageTitle = 'Thank You';
        $content = __DIR__ . '/views/thank_you.php';
        $routeFound = true;
        break;

    // Home page / Product List
    case '/':
    case '/products':
        $pageTitle = 'Home';
        $content = __DIR__ . '/views/products_list.php';
        (new ProductController())->list();
        break;

    // Default case for 404
    default:
        $pageTitle = 'Page Not Found';
        $content = __DIR__ . '/views/404.php';
        http_response_code(404);
        break;
}
