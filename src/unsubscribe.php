
<?php
session_start();
include 'functions.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['unsubscribe_email'])) {
        $_SESSION['unsubscribe_email'] = $_POST['unsubscribe_email'];
        $_SESSION['unsubscribe_code'] = generateVerificationCode();
        sendVerificationEmail($_SESSION['unsubscribe_email'], $_SESSION['unsubscribe_code']);
        $message = 'Verification code sent to your email for unsubscription.';
    } elseif (isset($_POST['unsubscribe_verification_code'])) {
        if ($_POST['unsubscribe_verification_code'] === $_SESSION['unsubscribe_code']) {
            unsubscribeEmail($_SESSION['unsubscribe_email']);
            $message = 'You have been unsubscribed.';
            unset($_SESSION['unsubscribe_code']);
        } else {
            $message = 'Invalid verification code.';
        }
    }
}
?>

<form method="POST">
    <input type="email" name="unsubscribe_email" required>
    <button id="submit-unsubscribe">Unsubscribe</button>
</form>

<form method="POST">
    <input type="text" name="unsubscribe_verification_code">
    <button id="verify-unsubscribe">Verify</button>
</form>

<p><?= $message ?></p>
