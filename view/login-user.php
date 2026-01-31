<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FloodGuard - User Login</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php include 'include/header.php'; ?>
<main>
    <div class="auth-container">
        <div class="auth-box">
            <h1>Log In</h1>
            <p class="subtitle">Input your credentials below.</p>
            
            <form action="process-login.php" method="POST" class="auth-form">
                <input type="hidden" name="role" value="user">
                
                <div class="form-group">
                    <label>Email / Phone Number</label>
                    <input type="text" name="email" placeholder="Enter your email address or phone number" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="submit-btn">Log In</button>
            </form>
            
            <p class="bottom-link">Don't have an account? <a href="register-user.php">Register here</a></p>
        </div>
    </div>
</main>
    <?php include 'include/footer.php'; ?>
</body>
</html>