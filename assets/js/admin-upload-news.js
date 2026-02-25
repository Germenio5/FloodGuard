/**
 * Admin Upload News Modal Handler
 * Manages the news upload form and submission
 */

function openNewsModal() {
    document.getElementById('newsModal').style.display = 'flex';
    document.getElementById('newsForm').reset();
    document.getElementById('filePreview').classList.remove('show');
    document.getElementById('charCount').textContent = '0';
}

function closeNewsModal() {
    document.getElementById('newsModal').style.display = 'none';
}

// Handle news form submission
document.addEventListener('DOMContentLoaded', function() {
    const newsForm = document.getElementById('newsForm');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('newsPhoto');
    const filePreview = document.getElementById('filePreview');
    const descriptionInput = document.getElementById('newsDescription');
    const charCount = document.getElementById('charCount');
    
    // Character counter
    if (descriptionInput) {
        descriptionInput.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
    
    // File upload area click
    if (fileUploadArea) {
        fileUploadArea.addEventListener('click', function() {
            fileInput.click();
        });
    }
    
    // Drag and drop
    if (fileUploadArea) {
        fileUploadArea.addEventListener('drageover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('dragover');
        });
        
        fileUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
        });
        
        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect();
            }
        });
    }
    
    // File input change
    if (fileInput) {
        fileInput.addEventListener('change', handleFileSelect);
    }
    
    function handleFileSelect() {
        const file = fileInput.files[0];
        
        if (!file) {
            filePreview.classList.remove('show');
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPEG or PNG image file');
            fileInput.value = '';
            filePreview.classList.remove('show');
            return;
        }
        
        // Validate file size (15MB)
        if (file.size > 15 * 1024 * 1024) {
            alert('File size exceeds 15MB limit');
            fileInput.value = '';
            filePreview.classList.remove('show');
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            filePreview.innerHTML = `
                <img src="${e.target.result}" class="preview-image" alt="Preview">
                <div class="preview-info">
                    <strong>✓ File Selected</strong>
                    ${file.name} (${(file.size / 1024).toFixed(2)} KB)
                </div>
                <button type="button" class="remove-file-btn" onclick="removeFile()">
                    Remove File
                </button>
            `;
            filePreview.classList.add('show');
        };
        reader.readAsDataURL(file);
    }
    
    if (newsForm) {
        newsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!fileInput.files.length) {
                alert('Please select an image file');
                return;
            }
            
            const formData = new FormData(this);
            const submitBtn = newsForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Posting...';
            
            fetch('../controllers/news-controller.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success animation
                    submitBtn.innerHTML = '✓ Posted Successfully!';
                    submitBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    
                    setTimeout(() => {
                        closeNewsModal();
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                        location.reload();
                    }, 1500);
                } else {
                    alert('Error: ' + (data.message || 'Failed to post news'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error posting news. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});

function removeFile() {
    document.getElementById('newsPhoto').value = '';
    document.getElementById('filePreview').classList.remove('show');
}

// Close modal when clicking outside of it
window.addEventListener('click', function(event) {
    const newsModal = document.getElementById('newsModal');
    if (newsModal && event.target === newsModal) {
        closeNewsModal();
    }
});
