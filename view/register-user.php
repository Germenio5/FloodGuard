<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FloodGuard - Register</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<main>
    <?php include 'include/header.php'; ?>
<body>
    <div class="auth-container">
        <div class="auth-box register-box">
            <h1>Register Account</h1>
            
            <form action="process-register.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" placeholder="Enter your first name" required>
                </div>
                
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" placeholder="Enter your last name" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter your email address" required>
                </div>
                
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="Enter your phone number" required>
                </div>
                
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="Enter your address" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create a password" required>
                </div>
                
                <button type="submit" class="submit-btn">Register</button>
            </form>
            
            <p class="bottom-link">Already have an account? <a href="login-user.php">Log in here</a></p>
        </div>
    </div>
    </main>
    <?php include 'include/footer.php'; ?>
</body>
</html>