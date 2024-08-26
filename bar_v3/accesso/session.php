<?php
// session.php
session_start(); // Avvia la sessione

// Impostazioni per la visualizzazione degli errori
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Verifica se l'utente è autenticato
if (!isset($_SESSION['user_id'])) {
    header('Location: accesso/accesso.php'); // Reindirizza all'accesso se non autenticato
    exit();
}

// Verifica il ruolo dell'utente
if ($_SESSION['user_role'] === 'user') {
    // L'utente è un cliente, può accedere solo alla dashboard
    if (basename($_SERVER['PHP_SELF']) !== 'index.php') { // in qualsiasi caso rimane invariato il path
        header('Location: ../cassabar/dashboard/index.php');
        exit();
    }
}

// Recupera l'ID cliente dalla sessione
$clienteId = $_SESSION['user_id'];
// echo 'User ID: ' . $_SESSION['user_id'];

// Verifica se è stato superato il limite di tentativi di login
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
    echo 'Troppi tentativi di login. Per favore riprova più tardi.';
    exit();
}

// Aggiungi altre verifiche o logiche relative alla sessione, se necessario
define('BASE_URL', 'http://localhost:8888/cassabar/');

?>
