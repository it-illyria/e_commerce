<?php include 'partials/_header.php'; ?>
<?php include 'partials/_navbar.php'; ?>

<div class="mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold text-center text-gray-800 mb-8">Checkout</h1>

    <!-- Checkout Form -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Shipping Details -->
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Shipping Details</h2>

            <!-- Form Action changed to submit_checkout.php -->
            <form id="checkout-form" onsubmit="return confirmSubmission()" action="thank_you.php" method="POST">
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

        <!-- Order Summary -->
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Order Summary</h2>

            <ul>
                <!-- Example Product -->
                <li class="flex justify-between py-3 border-b border-gray-300">
                    <span class="text-gray-700">Product 1</span>
                    <span class="text-gray-700">$25.00</span>
                </li>
                <li class="flex justify-between py-3 border-b border-gray-300">
                    <span class="text-gray-700">Product 2</span>
                    <span class="text-gray-700">$15.00</span>
                </li>
            </ul>

            <div class="mt-6 flex justify-between text-lg font-semibold">
                <span class="text-gray-700">Total</span>
                <span class="text-gray-800">$40.00</span>
            </div>
        </div>
    </div>

    <!-- Payment Method -->
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
    // Function to handle form submission confirmation
    function confirmSubmission() {
        // Show confirmation alert
        var confirmation = confirm("Are you sure you want to proceed with the checkout?");

        if (confirmation) {
            // Simulate form submission
            setTimeout(function () {
                // Success alert after form submission
                alert("Your checkout has been submitted successfully!");
            }, 1000);
            return true; // Allow form submission
        } else {
            return false; // Prevent form submission if canceled
        }
    }
</script>
