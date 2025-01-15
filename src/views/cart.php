<?php include 'partials/_header.php'; ?>
<?php include 'partials/_navbar.php'; ?>

<div class="container mx-auto mt-10 px-4">
    <h2 class="text-3xl font-semibold mb-6">Your Cart</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <p class="text-center text-gray-500">Your cart is empty!</p>
    <?php else: ?>
        <div class="bg-white shadow-lg rounded-lg p-6">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-6 text-sm font-medium text-left text-gray-600">Product</th>
                    <th class="py-3 px-6 text-sm font-medium text-left text-gray-600">Price</th>
                    <th class="py-3 px-6 text-sm font-medium text-left text-gray-600">Quantity</th>
                    <th class="py-3 px-6 text-sm font-medium text-left text-gray-600">Total</th>
                    <th class="py-3 px-6 text-sm font-medium"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-4 px-6 text-sm"><?php echo $item['name']; ?></td>
                        <td class="py-4 px-6 text-sm text-gray-700">
                            $<?php echo number_format($item['price'], 2); ?></td>
                        <td class="py-4 px-6 text-sm">
                            <label>
                                <input type="number" class="quantity w-16 p-2 border rounded-lg"
                                       value="<?php echo $item['quantity']; ?>" min="1">
                            </label>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-700">
                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td class="py-4 px-6 text-sm text-red-500 cursor-pointer">
                            <button class="remove-item hover:text-red-700">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="mt-6 flex justify-between items-center">
                <p class="text-lg font-semibold text-gray-800">Total: $<span
                            id="cart-total"><?php echo number_format($cartTotal, 2); ?></span></p>
                <button id="checkout"
                        onclick="window.location.href='/checkout';"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
                    Proceed to Checkout
                </button>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                // Update quantity
                $(".quantity").change(function () {
                    var productId = $(this).closest("tr").data("product-id");
                    var quantity = $(this).val();
                    $.ajax({
                        url: "/cart/updateQuantity",
                        method: "POST",
                        data: {productId: productId, quantity: quantity},
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data.cartUpdated) {
                                $("#cart-total").text(data.cartTotal);
                                // Update cart items
                            }
                        }
                    });
                });

                // Remove item
                $(".remove-item").click(function () {
                    var productId = $(this).closest("tr").data("product-id");
                    $.ajax({
                        url: "/cart/removeFromCart",
                        method: "POST",
                        data: {productId: productId},
                        success: function () {
                            location.reload();
                        }
                    });
                });

                // Proceed to checkout
                $("#checkout").click(function () {
                    window.location.href = "/checkout";
                });
            });
        </script>

    <?php endif; ?>
</div>

<?php include 'partials/_footer.php'; ?>
