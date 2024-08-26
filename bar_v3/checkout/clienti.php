<?php
header('Content-Type: application/json');

// Includi il file di accesso al DataBase
include '../include/db.php';

$categoriaId = isset($_GET['categoria']) ? $_GET['categoria'] : '';

$query = "SELECT c.id, c.nome, c.numero_stanza, c.saldo, cat.nome AS categoria_nome
          FROM clienti c
          LEFT JOIN categorie cat ON c.categoria_id = cat.id
          WHERE (:categoriaId = '' OR c.categoria_id = :categoriaId)";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':categoriaId', $categoriaId, PDO::PARAM_INT);
$stmt->execute();
$clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Restituzione dei clienti in formato JSON
echo json_encode($clienti);
?>
