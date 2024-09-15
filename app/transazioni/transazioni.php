<?php
//transazioni.php
// Includi il file di accesso al DataBase
include '../include/db.php';

// Query per recuperare tutte le transazioni con i dettagli del cliente
$query = "SELECT t.id, c.id AS cliente_id, c.nome, t.totale, t.data_ora AS data
          FROM transazioni t
          JOIN clienti c ON t.cliente_id = c.id
          ORDER BY t.data_ora DESC";

$stmt = $pdo->prepare($query);
$stmt->execute();

$transazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'transazioni' => $transazioni]);
