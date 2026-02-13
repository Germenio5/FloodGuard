    // Proximity buttons
    document.querySelectorAll('.proximity-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.proximity-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('proximityInput').value = this.getAttribute('data-value');
        });
    });

    // Status buttons
    document.querySelectorAll('.status-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.status-btn').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('statusInput').value = this.getAttribute('data-value');
        });
    });

    // Display selected file name
    function displayFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : '';
        document.getElementById('fileName').textContent = fileName ? 'Selected: ' + fileName : '';
    }

    // Submit report with validation
    function submitReport(type) {
        const form = document.getElementById('floodReportForm');
        
        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        
        if (!document.getElementById('proximityInput').value) {
            alert('Please select Proximity to Water');
            return false;
        }
        
        if (!document.getElementById('statusInput').value) {
            alert('Please select Status');
            return false;
        }
        
        // Set submission type and submit
        document.getElementById('submissionType').value = type;
        form.submit();
    }