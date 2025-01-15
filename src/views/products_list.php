<?php include 'partials/_header.php'; ?>
<?php include 'partials/_navbar.php'; ?>

<form method="GET" action="/product/search" class="mb-6 flex justify-center items-center space-x-2">
    <label>
        <input type="text" name="search"
               class="form-input border-2 border-gray-300 rounded-lg mt-2.5 p-2 w-80 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all ease-in-out duration-300"
               placeholder="Search for products..."/>
    </label>
    <button type="submit"
            class="btn bg-blue-600 text-white w-20 pt-2 py-2 rounded-lg hover:bg-blue-700 hover:scale-105 transition-all duration-300 ease-in-out shadow-md">
        Search
    </button>
</form>

<div class="flex justify-end items-center mr-6">
    <a href="/cart"
       class="relative flex items-center bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
        <!-- Cart Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 7h18l-1 10H4L3 7z"/>
        </svg>
        Cart
        <!-- Cart Item Count -->
        <span class="cart-count absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-2">
            <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
        </span>
    </a>
</div>


<div id="productCarousel" class="carousel slide mx-auto">
    <div class="carousel-inner mx-auto">
        <?php foreach ($products as $index => $product): ?>
            <div class="carousel-item mx-auto <?php echo $index === 0 ? 'active' : ''; ?>">
                <div class=" max-w-md mx-auto">
                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="Product Image">
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class=" absolute top-1/2 left-0"
            type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class=" absolute top-1/2 right-0"
            type="button" data-bs-target="#productCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>


<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        <?php foreach ($products as $product): ?>
            <div class="card border rounded-lg shadow-lg overflow-hidden">
                <!-- Static Image for Testing -->
                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" class="w-full h-48 object-cover"
                     alt="Product Image">
                <div class="card-body p-4">
                    <h5 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($product['name']); ?></h5>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="mt-2 text-lg text-gray-900">$<?php echo htmlspecialchars($product['price']); ?></p>
                </div>
                <div class="card-footer p-4 bg-gray-50 text-center">
                    <form method="POST" action="/cart/addToCart">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <button type="submit"
                                class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="flex justify-center mt-8">
        <nav class="flex justify-between items-center space-x-2">
            <?php if ($page > 1): ?>
                <a href="/product/list/<?php echo $page - 1; ?>"
                   class="text-blue-600 hover:text-blue-800">Previous</a>
            <?php endif; ?>

            <span class="text-gray-600">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="/product/list/<?php echo $page + 1; ?>" class="text-blue-600 hover:text-blue-800">Next</a>
            <?php endif; ?>
        </nav>
    </div>
</div>

<?php include __DIR__ . '/partials/_footer.php'; ?>
