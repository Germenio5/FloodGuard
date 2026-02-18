/**
 * Flood Report Form JavaScript
 * Handles form interactions, photo upload, and drag & drop
 */

// Select status badge and update hidden input
function selectStatus(button) {
    const badges = button.parentElement.querySelectorAll('.badge');
    badges.forEach(badge => badge.classList.remove('selected'));
    button.classList.add('selected');
    document.getElementById('statusInput').value = button.textContent.trim();
}

// Preview photo before upload
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Check file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            const img = document.getElementById('previewImage');
            const nameSpan = document.getElementById('fileName');
            
            img.src = e.target.result;
            nameSpan.textContent = 'File: ' + file.name;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

// Initialize form event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    
    // Validate form before submission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const statusVal = document.getElementById('statusInput').value.trim();
            if (statusVal === '') {
                alert('Please select a status (Safe, In Danger, Alert, or Danger)');
                e.preventDefault();
            }
        });
    }

    // Drag and drop functionality
    const fileInput = document.getElementById('photoInput');
    const uploadBtn = document.querySelector('.upload-btn');

    if (uploadBtn && fileInput) {
        // Prevent default drag/drop behavior
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadBtn.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area on drag
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadBtn.addEventListener(eventName, () => {
                uploadBtn.style.borderColor = '#667eea';
                uploadBtn.style.backgroundColor = '#f9f7ff';
            });
        });

        // Remove highlight when leaving drop area
        ['dragleave', 'drop'].forEach(eventName => {
            uploadBtn.addEventListener(eventName, () => {
                uploadBtn.style.borderColor = '#ccc';
                uploadBtn.style.backgroundColor = '#f0f0f0';
            });
        });

        // Handle dropped files
        uploadBtn.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            
            // Trigger preview
            if (files && files[0]) {
                previewPhoto(fileInput);
            }
        });
    }
});

// Prevent default drag/drop behavior
function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}
