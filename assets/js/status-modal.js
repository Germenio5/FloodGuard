// Status modal behavior (moved from inline script)
document.addEventListener('DOMContentLoaded', function () {
    const editBtn = document.getElementById('editStatusBtn');
    const modal = document.getElementById('statusModal');
    const overlay = modal.querySelector('.status-modal-overlay');
    const options = modal.querySelectorAll('.status-option');
    const saveBtn = document.getElementById('saveStatusBtn');
    const closeBtn = document.getElementById('closeStatusBtn');
    const currentText = document.getElementById('currentStatusText');
    const statusBadge = document.getElementById('statusBadge');

    function setBadgeClass(status) {
        if (!statusBadge) return;
        statusBadge.classList.toggle('danger', status === 'In Danger');
        statusBadge.classList.toggle('safe', status === 'Safe');
    }

    function openModal() {
        const container = modal.querySelector('.status-options');
        const current = container.getAttribute('data-current');
        options.forEach(btn => btn.classList.toggle('selected', btn.dataset.status === current));
        modal.setAttribute('aria-hidden', 'false');
        modal.classList.add('open');
    }

    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
        modal.classList.remove('open');
    }

    editBtn.addEventListener('click', function (e) {
        e.preventDefault();
        openModal();
    });

    overlay.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);

    options.forEach(btn => {
        btn.addEventListener('click', function () {
            options.forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    saveBtn.addEventListener('click', function () {
        const selected = Array.from(options).find(b => b.classList.contains('selected'));
        if (!selected) return closeModal();
        const newStatus = selected.dataset.status;

        // POST to server to persist change
        const form = new FormData();
        form.append('status', newStatus);

        fetch('../controllers/update-status.php', {
            method: 'POST',
            body: form,
            credentials: 'same-origin'
        }).then(res => res.json()).then(data => {
            if (data && data.success) {
                // update UI
                currentText.textContent = data.status;
                setBadgeClass(data.status === 'In Danger' ? 'In Danger' : 'Safe');
                modal.querySelector('.status-options').setAttribute('data-current', data.status);
            } else {
                alert((data && data.message) ? data.message : 'Failed to save status');
            }
        }).catch(err => {
            console.error('Status update error', err);
            alert('Error updating status');
        }).finally(() => closeModal());
    });

    // Initialize badge class based on initial container state
    (function init() {
        try {
            const container = modal.querySelector('.status-options');
            const current = container.getAttribute('data-current');
            setBadgeClass(current === 'In Danger' ? 'In Danger' : 'Safe');
        } catch (e) {
            // ignore if modal not present
        }
    })();
});
