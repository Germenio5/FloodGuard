function resendCode() {
    if (confirm('Resend verification code to your phone?')) {
        document.getElementById('resendForm').submit();
    }
}