<?php

// Includi il file di accesso al DataBase
include '../include/db.php';

$id = $_GET['id'];

$query = "SELECT * FROM prodotti WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->bindValue(1, $id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    echo json_encode(['success' => true, 'product' => $product]);
} else {
    echo json_encode(['success' => false, 'message' => 'Prodotto non trovato.']);
}

// Non c'è bisogno di chiudere esplicitamente la connessione con PDO, ma si può fare con:
$pdo = null;
?>
