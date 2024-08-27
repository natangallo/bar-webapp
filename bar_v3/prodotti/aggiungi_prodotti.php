<?php
// aggiungi_prodotti.php

// Includi il file di accesso al DataBase
include '../include/db.php';

// Funzione per gestire l'inserimento di un prodotto
function gestisciProdotto($pdo, $nome, $prezzo, $categoria) {
    // Verifica se il prodotto esiste già (case-insensitive per il nome)
    $query = "SELECT id FROM prodotti WHERE LOWER(nome) = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([strtolower($nome)]);
    $prodotto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($prodotto) {
        $prodottoId = $prodotto['id'];
        // Aggiorna i dati del prodotto esistente
        $query = "UPDATE prodotti SET nome = ?, prezzo = ?, categoria = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nome, $prezzo, $categoria, $prodottoId]);
        return ['success' => true, 'message' => 'Prodotto aggiornato con successo'];
    } else {
        // Se il prodotto non esiste, inseriscilo nel database
        $query = "INSERT INTO prodotti (nome, prezzo, categoria) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nome, $prezzo, $categoria]);
    }
}

// Ricevi i dati POST
$data = json_decode(file_get_contents('php://input'), true);

// Se si sta utilizzando un file CSV
if (isset($data['csv'])) {
    $csv = $data['csv'];
    $rows = explode("\n", trim($csv));

    foreach ($rows as $row) {
        $fields = str_getcsv($row);
        if (count($fields) == 3) {
            $nome = $fields[0];
            $prezzo = $fields[1];
            $categoria = $fields[2];

            try {
                gestisciProdotto($pdo, $nome, $prezzo, $categoria);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit;
            }
        }
    }
    echo json_encode(['success' => true]);

// Se si sta aggiungendo/modificando un prodotto via form
} else if (isset($data['nome'], $data['prezzo'], $data['categoria'])) {
    $nome = $data['nome'];
    $prezzo = $data['prezzo'];
    $categoria = $data['categoria'];

    try {
        gestisciProdotto($pdo, $nome, $prezzo, $categoria);
        echo json_encode(['success' => true, 'message' => 'Prodotto salvato con successo']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dati mancanti o formato non valido']);
}
?>
