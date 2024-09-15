<?php
// getCategorie.php

// Includi il file di accesso al DataBase
include '../include/db.php';


$query = "SELECT * FROM categorie";
$stmt = $pdo->prepare($query);
$stmt->execute();

$categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['categorie' => $categorie]);
?>
