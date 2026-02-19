function viewReport(reportId) {
    const modal = document.getElementById('reportModal');
    const modalBody = document.getElementById('modalBody');

    // Fetch fresh report data from server
    fetch(`../controllers/get-report.php?id=${encodeURIComponent(reportId)}`)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(report => {
            const imageSrc = report.image_path ? `../${report.image_path}` : '../assets/images/placeholder.png';

            modalBody.innerHTML = `
                <div class="detail-row">
                    <div class="detail-label">Report ID:</div>
                    <div class="detail-value">${report.id}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Location:</div>
                    <div class="detail-value">${escapeHtml(report.location || report.user_email || 'Unknown')}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value">
                        <span class="status-badge ${getBadgeClassJS(report.status)}">
                            ${escapeHtml(report.status)}
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Description:</div>
                    <div class="detail-value">${escapeHtml(report.description || '')}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date Submitted:</div>
                    <div class="detail-value">${escapeHtml(report.created_at)}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Photo:</div>
                    <img src="${imageSrc}" alt="No Image" class="report-image">
                </div>
            `;

            modal.style.display = 'block';
        })
        .catch(err => {
            modalBody.innerHTML = `<div class="detail-row">Failed to load report details.</div>`;
            modal.style.display = 'block';
            console.error(err);
        });
}

function getBadgeClassJS(status) {
    switch (status.toLowerCase()) {
        case 'safe':
            return 'status-safe';
        case 'in danger':
            return 'status-danger';
        default:
            return 'status-unknown';
    }
}

function closeModal() {
    document.getElementById('reportModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('reportModal');
    if (event.target == modal) {
        closeModal();
    }
}

// Simple HTML escape
function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}