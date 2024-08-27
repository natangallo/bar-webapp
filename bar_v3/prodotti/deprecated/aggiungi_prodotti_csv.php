<?php

// Includi il file di accesso al DataBase
include '../include/db.php';

// Ricezione dei dati POST
$data = json_decode(file_get_contents('php://input'), true);
$csv = $data['csv'];

$rows = explode("\n", trim($csv));
foreach ($rows as $row) {
    $fields = str_getcsv($row);
    if (count($fields) == 3) {
        $nome = $fields[0];
        $prezzo = $fields[1];
        $categoria = $fields[2];

        try {
            $stmt = $pdo->prepare("INSERT INTO prodotti (nome, prezzo, categoria) VALUES (:nome, :prezzo, :categoria)");
            $stmt->execute([':nome' => $nome, ':prezzo' => $prezzo, ':categoria' => $categoria]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
}

echo json_encode(['success' => true]);
?>
