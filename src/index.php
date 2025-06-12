

<?php
session_start(); 
include 'functions.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['code'] = generateVerificationCode();
        sendVerificationEmail($_SESSION['email'], $_SESSION['code']);
        $message = 'Verification code sent to your email.';
    } elseif (isset($_POST['verification_code'])) {
        if ($_POST['verification_code'] === $_SESSION['code']) {
            registerEmail($_SESSION['email']);
            $message = 'Email verified and registered!';
            unset($_SESSION['code']);
        } else {
            $message = 'Invalid verification code.';
        }
    }
}
?>

<form method="POST">
    <input type="email" name="email" required>
    <button id="submit-email">Submit</button>
</form>

<form method="POST">
    <input type="text" name="verification_code" maxlength="6" required>
    <button id="submit-verification">Verify</button>
</form>

<p><?= $message ?></p>

