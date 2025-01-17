<nav class="navbar">
    <div class="navbar-logo">
        <a href="/">MyShop</a>
    </div>

    <div class="navbar-links">
        <?php if (isset($_SESSION['username'])): ?>
            <div class="navbar-dropdown">
                <span class="navbar-user">Hello, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                <div class="dropdown-content">
                    <a href="/user/profile">Profile</a>
                    <a href="/user/logout">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="/user/login">Login</a>
        <?php endif; ?>
    </div>
</nav>
