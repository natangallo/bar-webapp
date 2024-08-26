<?php
// login.php
// Impostazioni per la visualizzazione degli errori
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Prepara la query per selezionare l'utente e il campo ruolo
$stmt = $pdo->prepare("SELECT id, password, ruolo, cliente_id FROM utenti WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se l'utente esiste e se la password è corretta
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['ruolo'];
    $_SESSION['cliente_id'] = $user['cliente_id']; // Aggiunto l'ID cliente alla sessione

    session_regenerate_id(true); // Prevenzione attacchi di session fixation
    logSuccessfulLogin($pdo, $user['id']);

    // Reindirizza l'utente in base al ruolo
    if ($user['ruolo'] === 'admin') {
        echo json_encode(['success' => true, 'redirect' => '../index.php']);
    } else {
        echo json_encode(['success' => true, 'redirect' => '../dashboard/index.php']);
    }
} else {
    logFailedLogin($pdo, $username);
    echo json_encode(['success' => false, 'message' => 'Credenziali non valide']);
}
?>