<?php include 'partials/_header.php'; ?>
<?php include 'partials/_navbar.php'; ?>

<?php
if (isset($_SESSION['profile_data']) && !empty($_SESSION['profile_data'])) {
    $profileData = $_SESSION['profile_data']; ?>

    <div class="profile-container max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">User Profile</h1>
        <div class="space-y-4">
            <p class="text-lg"><strong
                        class="text-gray-700">Username:</strong> <?= htmlspecialchars($profileData['username']) ?></p>
            <p class="text-lg"><strong
                        class="text-gray-700">Email:</strong> <?= htmlspecialchars($profileData['email']) ?></p>
            <p class="text-lg"><strong class="text-gray-700">Member
                    Since:</strong> <?= htmlspecialchars($profileData['created_at']) ?></p>
        </div>
    </div>

<?php } else { ?>
    <div class="text-center text-red-600 mt-6">
        <p>Profile data not found. Please try again.</p>
    </div>
<?php } ?>

<?php include __DIR__ . '/partials/_footer.php'; ?>
