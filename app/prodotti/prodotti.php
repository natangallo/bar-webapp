<?php
// Impostazioni per la visualizzazione degli errori
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Includi il file di accesso al DataBase
include '../include/db.php';

// Estrazione dei prodotti
$sql = "SELECT id, nome, prezzo, categoria FROM prodotti";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$prodotti = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Restituzione dei prodotti in formato JSON
header('Content-Type: application/json');
echo json_encode($prodotti);
?>
