// functionality for news page report detail modal
// requires a global boolean window.ALLOW_REPORT_DETAILS_ANONYMOUS

// read anonymous flag from body data attribute (1 means allowed)
const ALLOW_REPORT_DETAILS_ANONYMOUS = document.body && document.body.dataset.allowAnonymous === '1';

(function(){
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getBadgeClassJS(status) {
        if (status === 'Safe') return 'status-safe';
        if (status === 'At-Risk' || status === 'Danger') return 'status-danger';
        return 'status-unknown';
    }

    function viewReport(reportId) {
        const modal = document.getElementById('reportModal');
        const modalBody = document.getElementById('modalBody');
        fetch(`../controllers/get-report.php?id=${encodeURIComponent(reportId)}`)
            .then(res => {
                if (!res.ok) {
                    if (res.status === 403 && !ALLOW_REPORT_DETAILS_ANONYMOUS) {
                        window.location.href = '../views/login-user.php?error=login_required';
                        return Promise.reject('login required');
                    }
                    return res.json().then(err=>Promise.reject(err));
                }
                return res.json();
            })
            .then(report=>{
                let statusBadgeClass = getBadgeClassJS(report.status);
                modalBody.innerHTML = `
                    <div class="detail-row"><div class="detail-label">Location:</div><div class="detail-value">${escapeHtml(report.location)}</div></div>
                    <div class="detail-row"><div class="detail-label">Status:</div><div class="detail-value"><span class="status-badge ${statusBadgeClass}">${escapeHtml(report.status)}</span></div></div>
                    <div class="detail-row"><div class="detail-label">Description:</div><div class="detail-value">${escapeHtml(report.description)}</div></div>
                    <div class="detail-row"><div class="detail-label">Date Submitted:</div><div class="detail-value">${escapeHtml(report.created_at)}</div></div>
                    <div class="image-row">${report.image ? `<img src="data:image/jpeg;base64,${report.image}" class="report-image">` : ''}</div>
                `;
                modal.style.display='block';
            })
            .catch(err=>{
                console.error(err);
                if (typeof err === 'object' && err.error) {
                    modalBody.textContent = 'Unable to load details.';
                    modal.style.display='block';
                }
            });
    }

    function closeModal(){document.getElementById('reportModal').style.display='none';}

    document.addEventListener('click',function(e){
        if(e.target.classList.contains('menu')){
            const id=e.target.getAttribute('data-report-id');
            if(id) viewReport(id);
        }
        if(e.target==document.getElementById('reportModal')) closeModal();
    });
})();