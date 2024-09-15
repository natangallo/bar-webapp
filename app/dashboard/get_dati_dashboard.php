<?php
// get_dati_dashboard.php
include '../include/db.php';

session_start();

// Recupera l'ID cliente dalla query string o dalla sessione
// $clienteId = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];

// Assicurati che la sessione sia valida
if (!isset($_SESSION['user_id']) || !isset($_SESSION['cliente_id'])) {
    echo json_encode(['saldo' => false, 'prodotti' => [], 'transazioni' => []]);
    exit;
}

// Recupera l'ID cliente dalla sessione
$clienteId = $_SESSION['cliente_id'];

// Recupera il nome dell'utente dal database
$queryNome = "SELECT nome FROM clienti WHERE id = ?";
$stmtNome = $pdo->prepare($queryNome);
$stmtNome->execute([$clienteId]);
$nomeUtente = $stmtNome->fetchColumn(); // Ottiene il nome dell'utente

if (!$nomeUtente) {
    $nomeUtente = 'Utente'; // Fallback in caso di errore
}

// Funzione per recuperare il saldo
function getSaldo($pdo, $clienteId) {
    $querySaldo = "SELECT saldo FROM clienti WHERE id = ?";
    $stmtSaldo = $pdo->prepare($querySaldo);
    $stmtSaldo->execute([$clienteId]);
    return $stmtSaldo->fetchColumn();
}

// Funzione per recuperare le statistiche dei prodotti
function getStatisticheProdotti($pdo, $clienteId) {
    $queryProdotti = "SELECT p.nome AS prodotto_nome, SUM(dt.quantita) AS quantita_totale
                      FROM dettagli_transazione dt
                      JOIN prodotti p ON dt.prodotto_id = p.id
                      JOIN transazioni t ON dt.transazione_id = t.id
                      WHERE t.cliente_id = ?
                      GROUP BY p.nome";
    $stmtProdotti = $pdo->prepare($queryProdotti);
    $stmtProdotti->execute([$clienteId]);
    return $stmtProdotti->fetchAll(PDO::FETCH_ASSOC);
}

// Funzione per recuperare le transazioni
function getTransazioni($pdo, $clienteId) {
    $queryTransazioni = "SELECT t.id AS transazione_id, t.data_ora AS data, 
                                 t.descrizione, t.totale
                         FROM transazioni t
                         WHERE t.cliente_id = ?
                         ORDER BY t.data_ora DESC";
    $stmtTransazioni = $pdo->prepare($queryTransazioni);
    $stmtTransazioni->execute([$clienteId]);
    return $stmtTransazioni->fetchAll(PDO::FETCH_ASSOC);
}

// Recupera i dati
$saldo = getSaldo($pdo, $clienteId);
$prodotti = getStatisticheProdotti($pdo, $clienteId);
$transazioni = getTransazioni($pdo, $clienteId);

// Ritorna i dati in formato JSON
echo json_encode([
    'saldo' => $saldo,
    'prodotti' => $prodotti,
    'transazioni' => $transazioni,
    'nomeUtente' => $nomeUtente
]);
// echo "Cliente ID: " . $clienteId;
// echo "Nome Utente: " . $nomeUtente;


?>
