<?php
// getClienti.php

// Includi il file di accesso al DataBase
include '../include/db.php';

$categoriaId = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : '';

$query = "SELECT c.id, c.nome, c.numero_stanza, GROUP_CONCAT(cat.nome SEPARATOR ', ') AS categoria_nome, c.saldo
          FROM clienti c
          LEFT JOIN clienti_categorie cc ON c.id = cc.cliente_id
          LEFT JOIN categorie cat ON cc.categoria_id = cat.id
          WHERE (:categoria_id = '' OR cc.categoria_id = :categoria_id)
          GROUP BY c.id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':categoria_id', $categoriaId);
$stmt->execute();

$clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['clienti' => $clienti]);
