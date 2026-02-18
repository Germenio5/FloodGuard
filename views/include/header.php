<?php
define("BASE_URL", "/FloodGuard/views/");
$current_page = basename($_SERVER['PHP_SELF']);
$isLoggedIn = isset($_SESSION['email']);
?>

<link rel="stylesheet" href="../assets/css/header.css">
<link rel="stylesheet" href="assets/css/header.css">

<header class="header">

    <?php if ($current_page == 'index.php'): ?>

        <div class="logo disabled">
            <div class="logo-icon"></div>
            <span class="logo-text">FLOODGUARD</span>
        </div>

    <?php else: ?>

        <a href="../index.php" class="logo">
            <div class="logo-icon"></div>
            <span class="logo-text">FLOODGUARD</span>
        </a>

    <?php endif; ?>

    <nav>
        <ul class="nav-menu">
            <li>
                <a href="../index.php"
                   class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                   Home
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>aboutus.php"
                   class="<?= $current_page == 'aboutus.php' ? 'active' : '' ?>">
                   About Us
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>news.php"
                   class="<?= $current_page == 'news.php' ? 'active' : '' ?>">
                   News
                </a>
            </li>

            <li>
                <?php if($isLoggedIn): ?>
                    <a href="<?= BASE_URL ?>../controllers/log-out.php"
                       class="logout-link">
                       Log Out
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>login-user.php"
                       class="<?= $current_page == 'login-user.php' ? 'active' : '' ?>">
                       Log In
                    </a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</header>
