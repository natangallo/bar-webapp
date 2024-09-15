<?php
// conferma_transazione.php
// Includi il file di accesso al DataBase
include '../include/db.php';

// Ricezione dei dati POST
$data = json_decode(file_get_contents('php://input'), true);

$clienteId = $data['clienteId'];
$carrello = $data['carrello'];
$totale = str_replace(['€', ','], ['', '.'], $data['totale']); // Converti totale a formato numerico

try {
    header('Content-Type: application/json');

    // Avvio della transazione
    $pdo->beginTransaction();

    // Recupera il saldo attuale del cliente
    $stmt = $pdo->prepare("SELECT saldo FROM clienti WHERE id = :cliente_id");
    $stmt->execute([':cliente_id' => $clienteId]);
    $saldoAttuale = $stmt->fetchColumn();

    if ($saldoAttuale === false) {
        throw new Exception('Cliente non trovato.');
    }

    // Calcola il nuovo saldo
    $nuovoSaldo = $saldoAttuale - $totale;

    // Aggiornamento del saldo del cliente
    $stmt = $pdo->prepare("UPDATE clienti SET saldo = :nuovo_saldo WHERE id = :cliente_id");
    $stmt->execute([':nuovo_saldo' => $nuovoSaldo, ':cliente_id' => $clienteId]);

    // Inserimento della transazione
    $stmt = $pdo->prepare("INSERT INTO transazioni (cliente_id, totale) VALUES (:cliente_id, :totale)");
    $stmt->execute([':cliente_id' => $clienteId, ':totale' => $totale]);
    $transazioneId = $pdo->lastInsertId();

    // Inserimento dei dettagli della transazione
    $stmt = $pdo->prepare("INSERT INTO dettagli_transazione (transazione_id, prodotto_id, quantita) VALUES (:transazione_id, :prodotto_id, :quantita)");
    foreach ($carrello as $item) {
        $stmt->execute([
            ':transazione_id' => $transazioneId,
            ':prodotto_id' => $item['id'],
            ':quantita' => $item['quantita']
        ]);
    }

    // Completamento della transazione
    $pdo->commit();

    // Controlla se il saldo è negativo per mostrare un avviso
    if ($nuovoSaldo < 0) {
        echo json_encode(['success' => true, 'message' => 'Transazione completata! Avviso: il saldo è negativo.']);
    } else {
        echo json_encode(['success' => true]);
    }

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
