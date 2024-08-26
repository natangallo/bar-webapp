<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../include/db.php';

// Controlla se una sessione è già avviata prima di avviarne una nuova
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


    // Crea un nuovo utente amministratore
$username = 'admin';
 $password = password_hash('admin_password', PASSWORD_BCRYPT);

 $stmt = $pdo->prepare('INSERT INTO utenti (username, password, ruolo) VALUES (?, ?, ?)');
 $stmt->execute([$username, $password, 'admin']);

 echo "Admin user created.";
?>
