<?php include 'partials/_header.php'; ?>
<?php include 'partials/_navbar.php'; ?>

<div class="mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold text-center text-gray-800 mb-8">Checkout</h1>

    <?php if (isset($orderSuccess) && $orderSuccess): ?>
        <script>
            // Show success alert after order placement
            alert("Order placed successfully!");
        </script>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Shipping Details</h2>

            <form id="checkout-form" onsubmit="return confirmSubmission()" method="POST">
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-medium">Full Name</label>
                    <input type="text" id="name" name="name"
                           class="w-full p-4 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div class="mb-6">
                    <label for="address" class="block text-gray-700 font-medium">Address</label>
                    <textarea id="address" name="address" rows="4"
                              class="w-full p-4 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <div class="mb-6">
                    <label for="phone" class="block text-gray-700 font-medium">Phone Number</label>
                    <input type="text" id="phone" name="phone"
                           class="w-full p-4 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <button type="submit"
                        class="w-full py-4 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
                    Proceed to Payment
                </button>
            </form>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Order Summary</h2>

            <ul>
                <?php foreach ($cartProducts as $product): ?>
                    <li class="flex justify-between py-3 border-b border-gray-300">
                        <span class="text-gray-700"><?php echo htmlspecialchars($product['name']); ?></span>
                        <span class="text-gray-700">$<?php echo number_format($product['price'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="mt-6 flex justify-between text-lg font-semibold">
                <span class="text-gray-700">Total</span>
                <span class="text-gray-800">$<?php echo number_format($total, 2); ?></span>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Payment Method</h2>
        <div class="flex items-center space-x-6">
            <input type="radio" id="cash" name="payment_method" value="cash" class="w-6 h-6 text-blue-600">
            <label for="cash" class="text-gray-700">Cash on Delivery</label>
        </div>
    </div>
</div>

<?php include 'partials/_footer.php'; ?>

<script>
    function confirmSubmission() {
        return confirm("Are you sure you want to proceed with the checkout?");
    }
</script>
