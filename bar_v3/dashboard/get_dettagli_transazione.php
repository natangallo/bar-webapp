<?php
// get_dettagli_transazione.php
session_start();
require '../include/db.php';

$transazioneId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$queryDettagli = "SELECT p.nome AS prodotto_nome, dt.quantita, p.prezzo
                  FROM dettagli_transazione dt
                  JOIN prodotti p ON dt.prodotto_id = p.id
                  WHERE dt.transazione_id = ?";
$stmtDettagli = $pdo->prepare($queryDettagli);
$stmtDettagli->execute([$transazioneId]);
$dettagli = $stmtDettagli->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'dettagli' => $dettagli]);
?>
