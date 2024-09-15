<?php
// utils.php
function logFailedLogin($pdo, $username) {
    $stmt = $pdo->prepare("INSERT INTO login_attempts (username, attempt_time) VALUES (?, NOW())");
    $stmt->execute([$username]);
}

function countFailedLogins($pdo, $username, $minutes = 15) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE username = ? AND attempt_time > (NOW() - INTERVAL ? MINUTE)");
    $stmt->execute([$username, $minutes]);
    return $stmt->fetchColumn();
}

function isAccountLocked($pdo, $username) {
    $failedAttempts = countFailedLogins($pdo, $username);
    return $failedAttempts >= 5;
}

function logSuccessfulLogin($pdo, $userId) {
    $stmt = $pdo->prepare("INSERT INTO login_logs (user_id) VALUES (?)");
    $stmt->execute([$userId]);
}
?>
