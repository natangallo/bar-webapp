<?php
// login.php

session_start();
require '../include/db.php';
require 'utils.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = $data['password'];

if (isAccountLocked($pdo, $username)) {
    echo json_encode(['success' => false, 'message' => 'Account bloccato. Troppi tentativi di login.']);
    exit;
}

// Prepara la query per selezionare l'utente, la password e se la password è scaduta
$stmt = $pdo->prepare("SELECT id, password, ruolo, cliente_id, password_expired FROM utenti WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if (password_verify($password, $user['password'])) {
        if ($user['password_expired']) {
            // Password scaduta, richiedi cambio password
            echo json_encode(['success' => false, 'message' => 'La tua password è scaduta. Per favore, cambia la password.', 'password_expired' => true]);
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['ruolo'];
        $_SESSION['cliente_id'] = $user['cliente_id'];

        session_regenerate_id(true); // Prevenzione attacchi di session fixation
        logSuccessfulLogin($pdo, $user['id']);

        // Reindirizza l'utente in base al ruolo
        if ($user['ruolo'] === 'admin') {
            echo json_encode(['success' => true, 'redirect' => '../index.php']);
        } else {
            echo json_encode(['success' => true, 'redirect' => '../dashboard/utenti.php']);
        }
    } else {
        logFailedLogin($pdo, $username);
        echo json_encode(['success' => false, 'message' => 'Credenziali non valide']);
    }
} else {
    logFailedLogin($pdo, $username);
    echo json_encode(['success' => false, 'message' => 'Credenziali non valide']);
}
?>