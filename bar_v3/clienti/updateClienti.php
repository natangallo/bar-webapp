<?php
// updateClienti.php

include '../include/db.php';
include '../config/create_user.php'; 

// Funzione per gestire l'aggiunta o l'aggiornamento di un cliente
function gestisciCliente($pdo, $nome, $numeroStanza, $categorie) {
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
}

// Se si sta utilizzando un file CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csv'])) {
    $data = json_decode(file_get_contents('php://input'));
    $csv = $data->csv;

    $rows = explode("\n", $csv);
    foreach ($rows as $row) {
        $fields = str_getcsv($row);
        if (count($fields) >= 3) {
            $nome = trim($fields[0]);
            $numeroStanza = trim($fields[1]);
            $categoriaNome = trim($fields[2]);

            if ($nome && $numeroStanza) {
                // Gestisci il cliente con la categoria dal CSV
                gestisciCliente($pdo, $nome, $numeroStanza, [$categoriaNome]);
            } else {
                // Log o gestisci i dati mancanti
                error_log("Nome o numero stanza mancante nella riga CSV: $row");
            }
        }
    }

    // Aggiungi la creazione utenti dopo aver gestito i clienti
    ob_start(); // Inizia a catturare l'output
    include '../config/create_user.php'; // Richiama lo script per creare gli utenti
    $output = ob_get_clean(); // Ottieni l'output

    echo json_encode(['success' => true, 'message' => 'Cliente salvato con successo', 'output' => $output]);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : null;
    $numeroStanza = isset($_POST['numero-stanza']) ? trim($_POST['numero-stanza']) : null;
    $categorie = isset($_POST['categoria']) ? $_POST['categoria'] : [];  // Assicurati che sia un array

    if ($nome && $numeroStanza) {
        // Gestisci il cliente con le categorie fornite via form
        gestisciCliente($pdo, $nome, $numeroStanza, $categorie);
        
        // Aggiungi la creazione utenti dopo aver gestito i clienti
        ob_start(); // Inizia a catturare l'output
        include '../config/create_user.php'; // Richiama lo script per creare gli utenti
        $output = ob_get_clean(); // Ottieni l'output
        file_put_contents('debug_output.txt', $output); // Salva l'output in un file per verificarlo

        echo json_encode(['success' => true, 'message' => 'Cliente salvato con successo', 'output' => $output]);
    } else {
        // Gestisci l'errore: nome o numero stanza mancante
        echo json_encode(['success' => false, 'message' => 'Nome o numero di stanza mancante']);
    }
}
?>
