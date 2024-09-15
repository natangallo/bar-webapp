<?php
// File getTransazioniCliente.php

header('Content-Type: application/json');

// Includi il file di accesso al DataBase
include '../../include/db.php';

// Verifica che l'ID sia presente e sia valido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID non valido']);
    exit;
}

$id = $_GET['id'];

// Recupera i dettagli del cliente
$query = "SELECT c.*, GROUP_CONCAT(ca.categoria_id) AS categorie
          FROM clienti c
          LEFT JOIN clienti_categorie ca ON c.id = ca.cliente_id
          WHERE c.id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$id]);

$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    echo json_encode(['success' => false, 'message' => 'Cliente non trovato']);
    exit;
}

// Recupera le transazioni e i dettagli delle transazioni
$queryTransazioni = "SELECT t.id AS transazione_id, t.data_ora, t.descrizione, 
                             dt.quantita, p.nome AS prodotto_nome, p.prezzo,
                             (CASE 
                                 WHEN dt.transazione_id IS NOT NULL THEN (dt.quantita * p.prezzo)
                                 ELSE t.totale
                              END) AS totale
                     FROM transazioni t
                     LEFT JOIN dettagli_transazione dt ON t.id = dt.transazione_id
                     LEFT JOIN prodotti p ON dt.prodotto_id = p.id
                     WHERE t.cliente_id = ?
                     ORDER BY t.data_ora";
$stmtTransazioni = $pdo->prepare($queryTransazioni);
$stmtTransazioni->execute([$id]);
$transazioni = $stmtTransazioni->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'cliente' => $cliente,
    'transazioni' => $transazioni
]);
?>
