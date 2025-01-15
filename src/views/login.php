<?php include 'partials/_header.php'; ?>

<?php $showError = $showError ?? null; ?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login</h2>
        <form method="POST" action="/user/login">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
                <input type="text" id="username" name="username" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <input type="hidden" name="refurl"
                   value="<?php echo base64_encode($_SERVER['HTTP_REFERER'] ?? '/web/'); ?>">

            <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                Login
            </button>
        </form>

        <?php if ($showError): ?>
            <div class="mt-4 text-sm text-red-600 text-center">
                <?php echo htmlspecialchars($showError); ?>
            </div>
        <?php endif; ?>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account? <a href="/user/register" class="text-blue-500 hover:underline">Register here</a>.
            </p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/partials/_footer.php'; ?>
