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
