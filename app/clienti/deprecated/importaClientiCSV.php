<?php
// importaClientiCSV.php

// Includi il file di accesso al DataBase
include '../include/db.php';

$data = json_decode(file_get_contents('php://input'));
$csv = $data->csv;

$rows = explode("\n", $csv);
foreach ($rows as $row) {
    $fields = str_getcsv($row);
    if (count($fields) >= 3) {
        $nome = $fields[0];
        $numeroStanza = $fields[1];
        $categoriaNome = $fields[2];

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

        // Verifica se il cliente esiste già (case-insensitive per il nome)
        $query = "SELECT id FROM clienti WHERE LOWER(nome) = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([strtolower($nome)]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            $clienteId = $cliente['id'];

            // Aggiorna il numero di stanza del cliente esistente
            $query = "UPDATE clienti SET numero_stanza = ? WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$numeroStanza, $clienteId]);

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
        } else {
            // Se il cliente non esiste, inserirlo nel database
            $query = "INSERT INTO clienti (nome, numero_stanza) VALUES (?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$nome, $numeroStanza]);
            $clienteId = $pdo->lastInsertId();

            // Associa il cliente alla categoria
            $query = "INSERT INTO clienti_categorie (cliente_id, categoria_id) VALUES (?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$clienteId, $categoriaId]);
        }
    }
}

echo json_encode(['success' => true]);
?>
