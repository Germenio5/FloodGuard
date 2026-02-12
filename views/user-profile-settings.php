<?php
include '../controllers/profile-settings-controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/css/userprofilesettings.css">
</head>
<body>

<?php include 'include/user-sidebar.php'; ?>

<main class="profile-page">
    <div class="container">
        <div class="profile-layout">

            <!-- Left Side - Profile Photo -->
            <div class="profile-photo-section">
                <div class="photo-container">
                    <div class="profile-photo">
                        <?php if(empty($userData['profile_photo'])): ?>
                            <div class="photo-placeholder"></div>
                        <?php else: ?>
                            <img src="<?= htmlspecialchars($userData['profile_photo']) ?>" alt="Profile Photo">
                        <?php endif; ?>
                    </div>
                    <h2 class="profile-name"><?= htmlspecialchars($userData['username']) ?></h2>
                    <p class="profile-subtitle">Update your profile information</p>
                    <button class="change-photo-btn">Change Photo</button>
                </div>
            </div>

            <!-- Right Side - Profile Form -->
            <div class="profile-form-section">
                <form class="profile-form" method="POST" action="update-profile.php">
                    <!-- Username -->
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-input" 
                            value="<?= htmlspecialchars($userData['username']) ?>"
                        >
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <input 
                            type="text" 
                            id="address" 
                            name="address" 
                            class="form-input" 
                            value="<?= htmlspecialchars($userData['address']) ?>"
                        >
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            value="<?= htmlspecialchars($userData['email']) ?>"
                        >
                        <p class="form-note">We'll never share your email</p>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-input" 
                            value="<?= htmlspecialchars($userData['phone']) ?>"
                        >
                        <p class="form-note">For account recovery</p>
                    </div>

                    <!-- Change Password Link -->
                    <div class="form-group">
                        <a href="#" class="change-password-link" id="changePasswordLink">Change Current Password</a>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="reset" class="btn btn-reset">Reset</button>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</main>

<!-- Change Password Modal -->
<div class="modal-overlay" id="passwordModal">
    <div class="modal-content">
        <h2 class="modal-title">Create New Password</h2>
        
        <form class="password-form" method="POST" action="change-password.php">
            <!-- Current Password -->
            <div class="form-group">
                <label for="current-password" class="form-label">Current Password</label>
                <input 
                    type="password" 
                    id="current-password" 
                    name="current_password" 
                    class="form-input" 
                    placeholder="Enter your current password"
                    required
                >
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label for="new-password" class="form-label">New Password</label>
                <input 
                    type="password" 
                    id="new-password" 
                    name="new_password" 
                    class="form-input" 
                    placeholder="Enter your new password"
                    required
                >
            </div>

            <!-- Confirm New Password -->
            <div class="form-group">
                <label for="confirm-password" class="form-label">Confirm New Password</label>
                <input 
                    type="password" 
                    id="confirm-password" 
                    name="confirm_password" 
                    class="form-input" 
                    placeholder="Re-enter your new password"
                    required
                >
            </div>

            <!-- Modal Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-reset" id="cancelBtn">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/profile-settings.js"></script>

</body>
</html>
