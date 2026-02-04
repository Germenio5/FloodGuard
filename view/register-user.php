<?php
include '../controller/register-controller.php';
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FloodGuard - Register</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>

<body>
    <?php include 'include/header.php'; ?>

        <main>
            <div class="auth-container">
            <div class="auth-box register-box">

            <h1><?= $registerTitle ?></h1>

            <?php if($successMessage != ""): ?>
            <div class="success-box">
                <?= $successMessage ?>
            </div>
            <?php endif; ?>

            <form action="<?= $formAction ?>" 
                method="POST" 
                class="auth-form">

            <div class="form-group">
                <label>First Name</label>

                <input type="text" 
                    name="first_name"
                    value="<?= $old['first_name'] ?>"
                    placeholder="Enter your first name" required>

                <small class="error-box">
                    <?= $errors['first_name'] ?>
                </small>
            </div>

            <div class="form-group">
                <label>Last Name</label>

                <input type="text" 
                    name="last_name"
                    value="<?= $old['last_name'] ?>"
                    placeholder="Enter your last name" required>

                <small class="error-box">
                    <?= $errors['last_name'] ?>
                </small>
            </div>

            <div class="form-group">
                <label>Email</label>

                <input type="email" 
                    name="email"
                    value="<?= $old['email'] ?>"
                    placeholder="Enter your email address" required>

                <small class="error-box">
                    <?= $errors['email'] ?>
                </small>
            </div>

            <div class="form-group">
                <label>Phone Number</label>

                <input type="tel" 
                    name="phone"
                    value="<?= $old['phone'] ?>"
                    placeholder="Enter your phone number" required>

                <small class="error-box">
                    <?= $errors['phone'] ?>
                </small>
            </div>

            <div class="form-group">
                <label>Address</label>

                <input type="text" 
                    name="address"
                    value="<?= $old['address'] ?>"
                    placeholder="Enter your address" required>

                <small class="error-box">
                    <?= $errors['address'] ?>
                </small>
            </div>

            <div class="form-group">
                <label>Password</label>

                <input type="password" 
                    name="password"
                    placeholder="Create a password" required>

                <small class="error-box">
                    <?= $errors['password'] ?>
                </small>
            </div>

            <button type="submit" class="submit-btn">
                Register
            </button>

            </form>

            <p class="bottom-link">
            Already have an account? 
            <a href="../view/login-user.php">Log in here</a>
            </p>

            </div>
            </div>
        </main>

    <?php include 'include/footer.php'; ?>

</body>
</html>
