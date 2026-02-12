<?php
define("IMG", "../assets/images/");
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
                <a href="../index.php">Home</a>
                <a href="<?= BASE_URL ?>user-affected-areas.php">Monitor</a>
                <a href="<?= BASE_URL ?>user-water-level-data.php">Water Level Data</a>
                <a href="<?= BASE_URL ?>user-report-flood.php">Report</a>
            </nav>
        </div>
    </div>
</footer>
