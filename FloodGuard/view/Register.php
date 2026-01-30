<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FloodGuard - Login</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <?php include 'include/header.php'; ?>
<main>
    <div class="auth-container">
        <div class="auth-box">
            <h1>Log In</h1>
            <p class="subtitle">Please select your role to proceed.</p>
            
            <div class="role-buttons">
                <a href="LogInUser.php" class="role-btn">User</a>
                <a href="LogInAdmin.php" class="role-btn">Admin</a>
            </div>
        </div>
    </div>
    </main>
    <?php include 'include/footer.php'; ?>
</body>
</html>
