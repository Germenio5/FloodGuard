function viewReport(report) {
    const modal = document.getElementById('reportModal');
    const modalBody = document.getElementById('modalBody');
    
    let imageHtml = '';
    if (report.photo) {
        imageHtml = `
            <div class="detail-row">
                <div class="detail-label">Photo:</div>
                <img src="../uploads/${report.photo}" alt="Flood photo" class="report-image">
            </div>
        `;
    }
    
    modalBody.innerHTML = `
        <div class="detail-row">
            <div class="detail-label">Report ID:</div>
            <div class="detail-value">${report.id}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Location:</div>
            <div class="detail-value">${report.name}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Status:</div>
            <div class="detail-value">
                <span class="status-badge ${getBadgeClassJS(report.status)}">
                    ${report.status}
                </span>
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Description:</div>
            <div class="detail-value">${report.description}</div>
        </div>
        ${imageHtml}
        <div class="detail-row">
            <div class="detail-label">Date Submitted:</div>
            <div class="detail-value">${report.last_updated}</div>
        </div>
    `;
    
    modal.style.display = 'block';
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