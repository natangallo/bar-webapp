<?php
// aggiungiCliente.php

// Includi il file di accesso al DataBase
include '../include/db.php';


$nome = $_POST['nome'];
$numeroStanza = $_POST['numero-stanza'];
$categorie = isset($_POST['categoria']) ? $_POST['categoria'] : [];  // Assicurati che sia un array

// Verifica se il cliente esiste già (case-insensitive per il nome)
$query = "SELECT id FROM clienti WHERE LOWER(nome) = ? AND numero_stanza = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([strtolower($nome), $numeroStanza]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cliente) {
    $clienteId = $cliente['id'];
} else {
    // Se il cliente non esiste, inseriscilo nel database
    $query = "INSERT INTO clienti (nome, numero_stanza) VALUES (?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$nome, $numeroStanza]);
    $clienteId = $pdo->lastInsertId();
}

// Assicurati che $categorie sia un array
if (!is_array($categorie)) {
    $categorie = [$categorie];
}

foreach ($categorie as $categoriaNome) {
    // Converti la categoria in minuscolo per la verifica case-insensitive
    $categoriaNomeLower = strtolower($categoriaNome);

    // Verifica se la categoria esiste già (case-insensitive)
    $query = "SELECT id FROM categorie WHERE LOWER(nome) = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$categoriaNomeLower]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($categoria) {
        $categoriaId = $categoria['id'];
    } else {
        // Se la categoria non esiste, crearla
        $query = "INSERT INTO categorie (nome) VALUES (?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$categoriaNome]);
        $categoriaId = $pdo->lastInsertId();
    }

    // Verifica se il cliente è già associato alla categoria
    $query = "SELECT * FROM clienti_categorie WHERE cliente_id = ? AND categoria_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$clienteId, $categoriaId]);
    $clienteCategoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$clienteCategoria) {
        // Se il cliente non è associato alla categoria, aggiungila
        $query = "INSERT INTO clienti_categorie (cliente_id, categoria_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$clienteId, $categoriaId]);
    }
}

echo json_encode(['success' => true, 'message' => 'Cliente salvato con successo']);
