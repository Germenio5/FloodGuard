<?php
include '../controllers/verify-phone-controller.php';
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Phone - FloodGuard</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
    <?php include 'include/header.php'; ?>

        <main>
            <div class="auth-container">
            <div class="auth-box verify-box">

            <h1>Verify Your Phone Number</h1>

            <p class="verify-description">
                We've sent a 6-digit verification code to <strong><?php echo htmlspecialchars($maskedPhone ?? $userPhone ?? 'your phone'); ?></strong>.
                Enter the code below to activate your account.
            </p>

            <?php if($smsError): ?>
            <div class="error-box">
                SMS sending failed. Please try resending the code.
            </div>
            <?php endif; ?>

            <?php if($verificationError): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($verificationError); ?>
            </div>
            <?php endif; ?>

            <form action="../controllers/process-verify-phone.php" method="POST" class="auth-form">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($userEmail); ?>">

                <div class="form-group">
                    <label>Verification Code</label>
                    <input type="text"
                        name="verification_code"
                        placeholder="Enter 6-digit code"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        required>
                </div>

                <button type="submit" class="auth-btn">Verify Phone Number</button>
            </form>

            <div class="auth-links">
                Didn't receive the code?
                <a href="#" onclick="resendCode()">Resend Code</a>
            </div>

            <form id="resendForm" action="../controllers/resend-verification.php" method="POST" style="display: none;">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($userEmail); ?>">
            </form>

        </div>
    </div>
</main>

<script>
function resendCode() {
    if (confirm('Resend verification code to your phone?')) {
        document.getElementById('resendForm').submit();
    }
}
</script>

</body>
</html>