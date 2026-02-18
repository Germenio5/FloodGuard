// Password Modal Management
const passwordModal = document.getElementById('passwordModal');
const changePasswordLink = document.getElementById('changePasswordLink');
const cancelBtn = document.getElementById('cancelBtn');

changePasswordLink.addEventListener('click', function(e) {
    e.preventDefault();
    passwordModal.style.display = 'flex';
});

cancelBtn.addEventListener('click', function() {
    passwordModal.style.display = 'none';
});

passwordModal.addEventListener('click', function(e) {
    if (e.target === passwordModal) {
        passwordModal.style.display = 'none';
    }
});

// Photo Upload Management
const changePhotoBtn = document.getElementById('changePhotoBtn');
const photoInput = document.getElementById('photoInput');
const photoUpload = document.getElementById('photoUpload');
const photoPreview = document.getElementById('photoPreview');
const photoImg = document.getElementById('photoImg');

// Open file picker when "Change Photo" button is clicked
changePhotoBtn.addEventListener('click', function() {
    photoInput.click();
});

// Handle photo file selection
photoInput.addEventListener('change', function() {
    const file = this.files[0];
    
    if (file) {
        // File size validation (2MB max)
        const maxSize = 2 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('File is too large. Maximum size is 2MB.');
            this.value = '';
            return;
        }
        
        // File type validation
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Invalid file format. Please upload JPG, PNG, or GIF.');
            this.value = '';
            return;
        }
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            // Display preview
            if (photoImg) {
                photoImg.src = e.target.result;
            } else {
                // If no image exists, create one
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Profile Photo';
                img.id = 'photoImg';
                const placeholder = photoPreview.querySelector('.photo-placeholder');
                if (placeholder) {
                    placeholder.remove();
                }
                photoPreview.appendChild(img);
            }
        };
        reader.readAsDataURL(file);
        
        // Copy file to hidden upload input
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        photoUpload.files = dataTransfer.files;
    }
});

// Reset button functionality
const resetBtn = document.getElementById('resetBtn');
if (resetBtn) {
    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Reset form fields to original values
        document.getElementById('first_name').value = document.getElementById('originalFirstName').value;
        document.getElementById('last_name').value = document.getElementById('originalLastName').value;
        document.getElementById('address').value = document.getElementById('originalAddress').value;
        document.getElementById('email').value = document.getElementById('originalEmail').value;
        document.getElementById('phone').value = document.getElementById('originalPhone').value;
        
        // Clear photo file input
        document.getElementById('photoUpload').value = '';
        document.getElementById('photoInput').value = '';
    });
}

// Form validation
const profileForm = document.querySelector('.profile-form');
if (profileForm) {
    profileForm.addEventListener('submit', function(e) {
        // Get form inputs
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const address = document.getElementById('address').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        
        // Validate required fields
        if (!firstName || !lastName || !address || !email || !phone) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address.');
            return false;
        }
        
        // Validate phone format (basic validation for 9-10 digits)
        const phoneRegex = /^(\+?\d{1,3}[\s.-]?)?\d{9,10}$/;
        if (!phoneRegex.test(phone)) {
            e.preventDefault();
            alert('Please enter a valid phone number.');
            return false;
        }
    });
}

// Password validation in modal
const passwordForm = document.querySelector('.password-form');
if (passwordForm) {
    passwordForm.addEventListener('submit', function(e) {
        const currentPassword = document.getElementById('current-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        
        // Validate required fields
        if (!currentPassword || !newPassword || !confirmPassword) {
            e.preventDefault();
            alert('Please fill in all password fields.');
            return false;
        }
        
        // Check if passwords match
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('New passwords do not match.');
            return false;
        }
        
        // Validate password strength (min 8 chars, uppercase, lowercase, number)
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (!passwordRegex.test(newPassword)) {
            e.preventDefault();
            alert('New password must be at least 8 characters long and contain uppercase, lowercase, and numbers.');
            return false;
        }
    });
}

