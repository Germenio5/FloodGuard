<?php
define("IMG", "../images/");
?>

<link rel="stylesheet" href="../css/footer.css">
<link rel="stylesheet" href="css/footer.css">

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
                <a href="<?= BASE_URL ?>discussion.php">Discussion</a>
                <a href="<?= BASE_URL ?>aboutus.php">About Us</a>
                <a href="<?= BASE_URL ?>flooding.php">Flooding</a>
            </nav>
        </div>
    </div>
</footer>
