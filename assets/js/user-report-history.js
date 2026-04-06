/**
 * Show report details in modal
 */
function showReportDetails(reportId) {
    const modal = document.getElementById('reportDetailsModal');
    const body = document.getElementById('reportDetailsBody');
    
    // Show loading state
    body.innerHTML = '<div class="loading"><div class="spinner"></div><p>Loading details...</p></div>';
    modal.style.display = 'flex';

    // Fetch report details via AJAX
    fetch(`../controllers/get-report-details.php?id=${reportId}`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to fetch report details');
            return response.text();
        })
        .then(html => {
            body.innerHTML = html;
            // Update PDF download button
            document.getElementById('downloadPdfBtn').href = `../controllers/download-report-pdf.php?id=${reportId}`;
        })
        .catch(error => {
            body.innerHTML = `<div class="alert alert-error">Error loading report details: ${error.message}</div>`;
        });
}

/**
 * Close report details modal
 */
function closeReportDetails() {
    document.getElementById('reportDetailsModal').style.display = 'none';
}

/**
 * Close modal when clicking outside
 */
window.addEventListener('click', function(event) {
    const modal = document.getElementById('reportDetailsModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
});