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
        <!-- Success Message -->
        <?php if (!empty($successMessage)): ?>
            <div class="message-container success-message">
                <div class="message-content">
                    <span class="message-icon">✓</span>
                    <p><?= htmlspecialchars($successMessage) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (!empty($errorMessage)): ?>
            <div class="message-container error-message">
                <div class="message-content">
                    <span class="message-icon">⚠</span>
                    <p><?= htmlspecialchars($errorMessage) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="profile-layout">

            <!-- Left Side - Profile Photo -->
            <div class="profile-photo-section">
                <div class="photo-container">
                    <div class="profile-photo" id="photoPreview">
                        <?php if (!empty($userData['profile_photo']) && $userData['profile_photo'] !== '../assets/images/placeholder-profile.png'): ?>
                            <img src="<?= htmlspecialchars($userData['profile_photo']) ?>" alt="Profile Photo" id="photoImg">
                        <?php else: ?>
                            <div class="photo-placeholder">
                    
                            </div>
                        <?php endif; ?>
                    </div>
                    <h2 class="profile-name"><?= htmlspecialchars($userData['first_name'] . ' ' . $userData['last_name']) ?></h2>
                    <p class="profile-subtitle">Update your profile information</p>
                    <button type="button" class="change-photo-btn" id="changePhotoBtn">Change Photo</button>
                    <input type="file" id="photoInput" accept="image/jpeg,image/png,image/gif" style="display: none;">
                </div>
            </div>

            <!-- Right Side - Profile Form -->
            <div class="profile-form-section">
                <form class="profile-form" method="POST" action="../controllers/update-profile.php" enctype="multipart/form-data">
                    <!-- First Name -->
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            class="form-input" 
                            value="<?= htmlspecialchars($userData['first_name']) ?>"
                            required
                        >
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            class="form-input" 
                            value="<?= htmlspecialchars($userData['last_name']) ?>"
                            required
                        >
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="address" class="form-label">Address <span class="required">*</span></label>
                        <input 
                            type="text" 
                            id="address" 
                            name="address" 
                            class="form-input" 
                            value="<?= htmlspecialchars($userData['address']) ?>"
                            required
                        >
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            value="<?= htmlspecialchars($userData['email']) ?>"
                            required
                        >
                        <p class="form-note">We'll never share your email</p>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number <span class="required">*</span></label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-input" 
                            value="<?= htmlspecialchars($userData['phone']) ?>"
                            placeholder="09xxxxxxxxx"
                            required
                        >
                        <p class="form-note">For account recovery</p>
                    </div>

                    <!-- Hidden photo input for form -->
                    <input type="file" id="photoUpload" name="profile_photo" accept="image/jpeg,image/png,image/gif" style="display: none;">

                    <!-- Store original values for reset functionality -->
                    <input type="hidden" id="originalFirstName" value="<?= htmlspecialchars($userData['first_name']) ?>">
                    <input type="hidden" id="originalLastName" value="<?= htmlspecialchars($userData['last_name']) ?>">
                    <input type="hidden" id="originalAddress" value="<?= htmlspecialchars($userData['address']) ?>">
                    <input type="hidden" id="originalEmail" value="<?= htmlspecialchars($userData['email']) ?>">
                    <input type="hidden" id="originalPhone" value="<?= htmlspecialchars($userData['phone']) ?>">

                    <!-- Change Password Link -->
                    <div class="form-group">
                        <a href="#" class="change-password-link" id="changePasswordLink">Change Current Password</a>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="button" id="resetBtn" class="btn btn-reset">Reset</button>
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
        
        <form class="password-form" method="POST" action="../controllers/change-password.php">
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
                    placeholder="Enter your new password (min 8 characters, uppercase, lowercase, number)"
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
