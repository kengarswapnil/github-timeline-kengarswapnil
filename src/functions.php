
<?php
function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    $updated = array_filter($emails, fn($e) => trim($e) !== trim($email));
    file_put_contents($file, implode(PHP_EOL, $updated) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    $subject = isset($_SESSION['unsubscribe_code']) ? 'Confirm Unsubscription' : 'Your Verification Code';
    $body = isset($_SESSION['unsubscribe_code'])
        ? "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>"
        : "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: no-reply@example.com' . "\r\n";
    mail($email, $subject, $body, $headers);
}

function fetchGitHubTimeline() {
    return '<tr><td>Push</td><td>testuser</td></tr>'; // Placeholder
}

function formatGitHubData($data) {
    return '<h2>GitHub Timeline Updates</h2>
    <table border="1">
      <tr><th>Event</th><th>User</th></tr>' . $data . '</table>';
}

function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) return;
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $data = fetchGitHubTimeline();
    $formatted = formatGitHubData($data);
    $headers = "MIME-Version: 1.0\r\n" .
               "Content-type:text/html;charset=UTF-8\r\n" .
               "From: no-reply@example.com\r\n";
    foreach ($emails as $email) {
        $body = $formatted . "<p><a href='http://yourdomain.com/src/unsubscribe.php?email=$email' id='unsubscribe-button'>Unsubscribe</a></p>";
        mail($email, 'Latest GitHub Updates', $body, $headers);
    }
}
?>
