function resendOTP() {
    if (confirm('Resend OTP code to your phone?')) {
        document.getElementById('resendForm').submit();
    }
}