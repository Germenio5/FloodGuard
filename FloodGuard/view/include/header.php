<?php
define("BASE_URL", "/FloodGuard/");
$current_page = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="<?= BASE_URL ?>css/header.css">

<header class="header">
    <div class="logo">
        <div class="logo-icon"></div>
        <span class="logo-text">FLOODGUARD</span>
    </div>

    <nav>
        <ul class="nav-menu">
            <li>
                <a href="<?= BASE_URL ?>index.php"
                   class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                   Home
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>view/aboutus.php"
                   class="<?= $current_page == 'aboutus.php' ? 'active' : '' ?>">
                   About Us
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>view/news.php"
                   class="<?= $current_page == 'news.php' ? 'active' : '' ?>">
                   News
                </a>
            </li>

            <li>
                <a href="<?= BASE_URL ?>view/register.php"
                   class="<?= $current_page == 'register.php' ? 'active' : '' ?>">
                   Register
                </a>
            </li>
        </ul>
    </nav>
</header>
