<?php

// Includi il file di accesso al DataBase
include '../include/db.php';

// Recupera i dati inviati con POST
$data = json_decode(file_get_contents("php://input"));

$id = $data->id;
$nome = $data->nome;
$prezzo = $data->prezzo;
$categoria = $data->categoria;

$query = "UPDATE prodotti SET nome = ?, prezzo = ?, categoria = ? WHERE id = ?";
$stmt = $pdo->prepare($query);

// Utilizza bindValue o bindParam per associare i parametri alla query
$stmt->bindValue(1, $nome, PDO::PARAM_STR);
$stmt->bindValue(2, $prezzo, PDO::PARAM_STR);  // Se il prezzo è un decimale, puoi usare PDO::PARAM_STR o PDO::PARAM_INT a seconda del tipo di dato.
$stmt->bindValue(3, $categoria, PDO::PARAM_STR);
$stmt->bindValue(4, $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Errore HAHAHA durante l\'aggiornamento del prodotto.']);
}

// Non è necessario chiudere manualmente la connessione con PDO
$pdo = null;
?>
