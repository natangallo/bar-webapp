<?php

// Includi il file di accesso al DataBase
include '../include/db.php';

// Ricezione dei dati POST
$data = json_decode(file_get_contents('php://input'), true);
$nome = $data['nome'];
$prezzo = $data['prezzo'];
$categoria = $data['categoria'];

try {
    $stmt = $pdo->prepare("INSERT INTO prodotti (nome, prezzo, categoria) VALUES (:nome, :prezzo, :categoria)");
    $stmt->execute([':nome' => $nome, ':prezzo' => $prezzo, ':categoria' => $categoria]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
