<?php

// Includi il file di accesso al DataBase
include '../include/db.php';

// Verifica se è stato caricato un file CSV
if (isset($_FILES['csv']) && $_FILES['csv']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['csv']['tmp_name'];
    $handle = fopen($file, 'r');

    if ($handle !== false) {
        fgetcsv($handle); // Salta l'intestazione del CSV

        $query = "INSERT INTO clienti (nome) VALUES (?)";
        $stmt = $pdo->prepare($query);

        while (($data = fgetcsv($handle)) !== false) {
            $nome = $data[0];
            $stmt->execute([$nome]);
        }

        fclose($handle);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Impossibile aprire il file CSV.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nessun file CSV caricato.']);
}
