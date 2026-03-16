<?php
// Define BASE_URL if not already defined (for independent footer usage)
if (!defined("BASE_URL")) {
    define("IMG", "../assets/images/");
} else {
    define("IMG", "../assets/images/");
}
?>

<link rel="stylesheet" href="../assets/css/footer.css">
<link rel="stylesheet" href="assets/css/footer.css">

<footer class="footer">
    <div class="footer-content">
        <div class="footer-left">
            <div class="social-icons">
                <a href="#"><img src="<?= IMG ?>instagram.png" alt="Instagram"></a>
                <a href="#"><img src="<?= IMG ?>twitter.png" alt="Twitter"></a>
                <a href="#"><img src="<?= IMG ?>gmail.png" alt="Email"></a>
                <a href="#"><img src="<?= IMG ?>facebook.png" alt="Facebook"></a>
                <a href="#"><img src="<?= IMG ?>linkedin.png" alt="LinkedIn"></a>
            </div>

            <p class="copyright">
                © 2026 FloodGuard. All rights reserved.
            </p>
        </div>

        <div class="footer-right">
            <nav class="footer-nav">
                <?php
                // when the footer is included from index.php, ../index.php would point outside the
                // document root and lead to an error. disable the link on the home page instead.
                $currentPage = basename($_SERVER['PHP_SELF']);
                if ($currentPage === 'index.php') :
                ?>
                    <span class="disabled-link">Home</span>
                <?php else : ?>
                    <a href="../index.php">Home</a>
                <?php endif; ?>

                <?php
                    $aboutHref = $currentPage === 'index.php' ? '#about' : '/FloodGuard/index.php#about';
                    $howHref = $currentPage === 'index.php' ? '#how' : '/FloodGuard/index.php#how';
                    $featuresHref = $currentPage === 'index.php' ? '#features' : '/FloodGuard/index.php#features';
                ?>

                <a href="<?= $aboutHref ?>">About</a>
                <a href="<?= $howHref ?>">How It Works</a>
                <a href="<?= $featuresHref ?>">Features</a>

                <a href="<?= BASE_URL ?>user-affected-areas.php">Monitor</a>
                <a href="<?= BASE_URL ?>user-water-level-data.php">Water Level Data</a>
                <a href="<?= BASE_URL ?>user-report-flood.php">Report</a>
            </nav>
        </div>
    </div>
</footer>
