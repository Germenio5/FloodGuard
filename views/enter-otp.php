<?php
include '../controllers/enter-otp-controller.php';
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP - FloodGuard</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
    <?php include 'include/header.php'; ?>

        <main>
            <div class="auth-container">
            <div class="auth-box enter-otp-box">

            <h1>Enter OTP Code</h1>

            <p class="reset-description">
                We've sent a 6-digit OTP to your phone <strong><?php echo htmlspecialchars($censoredPhone); ?></strong>. Enter the code below to proceed with password reset.
            </p>

            <?php if($errorMessage): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
            <?php endif; ?>

            <form action="../controllers/process-enter-otp.php" method="POST" class="auth-form">
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

                <button type="submit" class="auth-btn">Verify OTP</button>
            </form>

            <div class="auth-links">
                <a href="#" onclick="resendOTP()">Didn't receive the code? Resend</a>
            </div>

            <form id="resendForm" action="../controllers/resend-otp.php" method="POST" style="display: none;">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($userEmail); ?>">
            </form>

        </div>
    </div>
</main>

<script src="../assets/js/enter-otp.js"></script>

</body>
</html>