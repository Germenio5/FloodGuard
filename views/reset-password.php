<?php
include '../controllers/reset-password-controller.php';
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
            <div class="auth-box reset-box">

            <h1>Reset Your Password</h1>

            <p class="reset-description">
                We've sent a 6-digit OTP to your phone. Enter the OTP and your new password below.
            </p>

            <?php if($errorMessage): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
            <?php endif; ?>

            <form action="../controllers/process-reset-password.php" method="POST" class="auth-form">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($userEmail); ?>">

                <div class="form-group">
                    <label>OTP Code</label>
                    <input type="text"
                        name="otp"
                        placeholder="Enter 6-digit OTP"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        required>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password"
                        name="new_password"
                        placeholder="Enter new password"
                        minlength="8"
                        required>
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password"
                        name="confirm_password"
                        placeholder="Confirm new password"
                        minlength="8"
                        required>
                </div>

                <button type="submit" class="auth-btn">Reset Password</button>
            </form>

        </div>
    </div>
</main>

</body>
</html>