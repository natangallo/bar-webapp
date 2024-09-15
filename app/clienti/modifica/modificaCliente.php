<?php
header('Content-Type: application/json');

// Includi l'accesso al DataBase
include '../../include/db.php';

// Verifica che l'ID sia presente e valido
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID non valido']);
    exit;
}

$id = $_POST['id'];
$nome = isset($_POST['nome']) ? $_POST['nome'] : null;
$numeroStanza = isset($_POST['numero_stanza']) ? $_POST['numero_stanza'] : null;
$categorie = isset($_POST['categorie']) ? $_POST['categorie'] : [];
$saldoIncremento = isset($_POST['saldo_incremento']) ? floatval($_POST['saldo_incremento']) : 0.00;

try {
    // Inizia una transazione
    $pdo->beginTransaction();

    // Aggiorna i dettagli del cliente
    $query = "UPDATE clienti
              SET nome = :nome, numero_stanza = :numero_stanza
              WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':nome' => $nome,
        ':numero_stanza' => $numeroStanza,
        ':id' => $id
    ]);

    // Aggiorna il saldo del cliente e aggiungi la transazione
    if ($saldoIncremento !== 0.00) {
        $querySaldo = "UPDATE clienti
                       SET saldo = saldo + :saldo_incremento
                       WHERE id = :id";
        $stmtSaldo = $pdo->prepare($querySaldo);
        $stmtSaldo->execute([
            ':saldo_incremento' => $saldoIncremento,
            ':id' => $id
        ]);

        // Inserisci la transazione nella tabella transazioni
        $queryTransazione = "INSERT INTO transazioni (cliente_id, totale, descrizione, data_ora)
                             VALUES (:cliente_id, :totale, :descrizione, NOW())";
        $stmtTransazione = $pdo->prepare($queryTransazione);
        $stmtTransazione->execute([
            ':cliente_id' => $id,
            ':totale' => $saldoIncremento,
            ':descrizione' => 'Aggiunta di saldo al conto'
        ]);
    }

    // Aggiorna le categorie del cliente
    $queryDeleteCategorie = "DELETE FROM clienti_categorie WHERE cliente_id = :id";
    $stmtDeleteCategorie = $pdo->prepare($queryDeleteCategorie);
    $stmtDeleteCategorie->execute([':id' => $id]);

    // Inserisci le nuove categorie
    foreach ($categorie as $categoriaId) {
        $queryInsertCategoria = "INSERT INTO clienti_categorie (cliente_id, categoria_id) VALUES (:id, :categoria_id)";
        $stmtInsertCategoria = $pdo->prepare($queryInsertCategoria);
        $stmtInsertCategoria->execute([
            ':id' => $id,
            ':categoria_id' => $categoriaId
        ]);
    }

    // Commit della transazione
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Cliente aggiornato con successo!']);
} catch (Exception $e) {
    // Rollback della transazione in caso di errore
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento: ' . $e->getMessage()]);
}
?>
