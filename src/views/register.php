<?php include 'partials/_header.php'; ?>

<?php $showError = $showError ?? null; ?>

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Register</h2>
        <form method="POST" action="/user/register">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
                <input type="text" id="username" name="username" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                Register
            </button>
        </form>

        <?php if ($showError): ?>
            <div class="mt-4 text-sm text-red-600 text-center">
                <?php echo htmlspecialchars($showError); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Client-side password confirmation validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function (e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
        }
    });
</script>

<?php include __DIR__ . '/partials/_footer.php'; ?>
