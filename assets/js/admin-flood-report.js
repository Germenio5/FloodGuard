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
            let imageSrc;
            if (report.image) {
                imageSrc = `data:image/jpeg;base64,${report.image}`; // assuming jpeg, browsers will handle types
            } else {
                imageSrc = '../assets/images/placeholder.png';
            }

            let smsButtonHtml = '';
            const canSendSMS = report.user_phone && isDangerStatus(report.status);
            const alreadySent = !!report.sms_sent_at;

            if (canSendSMS) {
                const buttonLabel = alreadySent ? 'Resend SMS Notification' : 'Send SMS Notification';
                const sentText = alreadySent ? `<div class="sms-sent-row"><span class="sms-sent-text">SMS notification sent on ${escapeHtml(report.sms_sent_at)}</span></div>` : '';

                smsButtonHtml = `${sentText}
                    <div class="sms-button-row">
                        <button class="send-sms-btn" onclick="sendSMS(${report.id}, ${alreadySent ? 'true' : 'false'})">
                            ${buttonLabel}
                        </button>
                    </div>`;
            }

            modalBody.innerHTML = `
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
                ${smsButtonHtml}
                <div class="image-row">
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

function isDangerStatus(status) {
    if (!status) return false;
    const s = status.toLowerCase().trim();
    return s === 'danger' || s === 'in danger';
}

function getBadgeClassJS(status) {
    switch (status) {
        case 'Safe':
            return 'badge-safe';
        case 'Alert':
            return 'badge-alert';
        case 'Danger':
            return 'badge-danger';
        default:
            return '';
    }
}

function sendSMS(reportId, forceSend = false) {
    const confirmationText = forceSend ?
        'SMS has already been sent. Do you want to resend it?' :
        'Are you sure you want to send an SMS notification to the user?';
    if (!confirm(confirmationText)) {
        return;
    }

    const button = document.querySelector('.send-sms-btn');
    const originalText = button ? button.textContent : '';
    if (button) {
        button.textContent = 'Sending...';
        button.disabled = true;
    }

    const params = new URLSearchParams();
    params.append('report_id', reportId);
    if (forceSend) params.append('force', '1');

    fetch('../controllers/send-sms.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params.toString()
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('SMS notification sent successfully!');
            // Refresh the modal to show updated status
            viewReport(reportId);
        } else {
            alert('Failed to send SMS: ' + data.message);
            if (button) {
                button.textContent = originalText;
                button.disabled = false;
            }
        }
    })
    .catch(err => {
        alert('Error sending SMS notification');
        console.error(err);
        if (button) {
            button.textContent = originalText;
            button.disabled = false;
        }
    });
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