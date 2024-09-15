<?php
// aggiungiCategoria.php

// Includi il file di accesso al DataBase
include '../include/db.php';

$nome = $_POST['categoria-nome'];

$query = "INSERT INTO categorie (nome) VALUES (?)";
$stmt = $pdo->prepare($query);
if ($stmt->execute([$nome])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
