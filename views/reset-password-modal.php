<?php
include '../controllers/reset-password-modal-controller.php';
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - FloodGuard</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
    <?php include 'include/header.php'; ?>

        <main>
            <div class="auth-container">
            <div class="auth-box">

            <h1>Reset Your Password</h1>
            <p class="subtitle">
                Enter your email address and we'll send you an OTP to reset your password.
            </p>

        <!-- ERROR MESSAGE -->
        <?php if(!empty($errorMessage)): ?>
            <div class="error-box">
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>

        <form action="../controllers/forgot-password.php" method="POST" class="auth-form">

            <div class="form-group">
                <label>Email Address</label>
                <input type="email"
                    name="email"
                    value="<?= htmlspecialchars($oldEmail) ?>"
                    placeholder="Enter your email address"
                    required>
            </div>

            <button type="submit" class="submit-btn">
                Send OTP
            </button>

        </form>

        <p class="bottom-link">
            Remember your password?
            <a href="login-user.php">Log in here</a>
        </p>

        </div>
        </div>

    </main>

    <?php include 'include/footer.php'; ?>

    </body>
</html>