function openEditModal(button) {
    const areaId = button.dataset.areaId;
    const bridge = button.dataset.bridge;
    const location = button.dataset.location;
    const current = parseFloat(button.dataset.current) || 0;
    const max = parseFloat(button.dataset.max) || 0;
    const speed = button.dataset.speed;
    const status = button.dataset.status;
    const percent = parseFloat(button.dataset.percent) || 0;

    document.getElementById('modalAreaId').value = areaId;
    document.getElementById('modalBridge').textContent = bridge;
    document.getElementById('modalLocation').textContent = location;
    document.getElementById('modalCurrent').value = current;
    document.getElementById('modalMax').textContent = max + 'm';
    document.getElementById('modalSpeed').textContent = speed;
    document.getElementById('modalStatus').textContent = status;
    document.getElementById('modalPercent').textContent = percent.toFixed(1) + '%';

    document.getElementById('editDataModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editDataModal').style.display = 'none';
}

// Attach listeners after DOM loads
window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-data-btn').forEach(btn => {
        btn.addEventListener('click', () => openEditModal(btn));
    });

    const form = document.getElementById('editDataForm');
    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                if (data.success) {
                    // Update card DOM
                    const card = document.querySelector(`.bridge-card[data-area-id="${data.area_id}"]`);
                    if (card) {
                        const currentEl = card.querySelector('.current-value');
                        const maxEl = card.querySelector('.max-value');
                        const percentEl = card.querySelector('.percent-value');
                        const speedEl = card.querySelector('.speed-value');
                        const progressFill = card.querySelector('.progress-fill');
                        const statusWrapper = card.querySelector('.status-badge-wrapper');

                        if (currentEl) currentEl.textContent = data.current_level;
                        if (maxEl) maxEl.textContent = data.max_level;
                        if (percentEl) percentEl.textContent = data.percentage;
                        if (progressFill) {
                            progressFill.style.width = data.percentage + '%';
                            progressFill.className = 'progress-fill progress-' + data.status;
                        }
                        if (statusWrapper) {
                            statusWrapper.innerHTML = getStatusBadgeHtml(data.status);
                        }

                        // Keep the edit button dataset in sync (so reopening modal shows updated values)
                        const editBtn = card.querySelector('.edit-data-btn');
                        if (editBtn) {
                            editBtn.dataset.current = parseFloat(data.current_level) || 0;
                            editBtn.dataset.max = parseFloat(data.max_level) || 0;
                            editBtn.dataset.percent = parseFloat(data.percentage) || 0;
                            editBtn.dataset.status = data.status;
                        }
                    }

                    // Update modal values too
                    document.getElementById('modalCurrent').value = parseFloat(data.current_level) || 0;
                    document.getElementById('modalMax').textContent = data.max_level;
                    document.getElementById('modalStatus').textContent = data.status;
                    document.getElementById('modalPercent').textContent = data.percentage + '%';

                    closeEditModal();
                    alert('Water level data updated successfully.');
                } else {
                    alert('Update failed. Please try again.');
                }
            } catch (err) {
                console.error(err);
                alert('Something went wrong while updating.');
            }
        });
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', (event) => {
        const modal = document.getElementById('editDataModal');
        if (event.target === modal) {
            closeEditModal();
        }
    });
});

function getStatusBadgeHtml(status) {
    switch (status.toLowerCase()) {
        case 'normal':
            return '<span class="status-badge status-normal">● Normal</span>';
        case 'warning':
            return '<span class="status-badge status-warning">● Warning</span>';
        case 'danger':
            return '<span class="status-badge status-danger">● Danger</span>';
        case 'critical':
            return '<span class="status-badge status-critical">● Critical</span>';
        default:
            return '<span class="status-badge">Unknown</span>';
    }
}