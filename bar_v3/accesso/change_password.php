<?php
session_start();
require '../include/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$newPassword = $data['newPassword'];

// Cripta la nuova password
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Aggiorna la password e resetta il campo password_expired
$stmt = $pdo->prepare("UPDATE utenti SET password = ?, password_expired = 0 WHERE username = ?");
if ($stmt->execute([$hashedPassword, $username])) {
    echo json_encode(['success' => true, 'message' => 'Password cambiata con successo.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Errore durante il cambio password.']);
}
?>
