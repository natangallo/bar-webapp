<?php
// config/create_user.php
include '../include/db.php';

// Funzione per generare una password sicura
if (!function_exists('generatePassword')) {
	function generatePassword($clienteNome) {
    	$nome = preg_replace('/\s+/', '', strtolower($clienteNome)); // Rimuove spazi e converte in minuscolo
    return $nome . '123'; // Aggiungi un suffisso numerico per maggiore sicurezza
}
}
// Funzione per generare un username basato sul nome del cliente
if (!function_exists('generateUsername')) {
	function generateUsername($clienteNome) {
    $nome = preg_replace('/\s+/', '', strtolower($clienteNome)); // Rimuove spazi e converte in minuscolo
    return $nome; // Username semplice basato sul nome del cliente
}
}

$output = ""; // Variabile per accumulare l'output

// Recupera i clienti senza un utente associato
$queryClienti = "SELECT clienti.id AS cliente_id, clienti.nome 
                 FROM clienti 
                 LEFT JOIN utenti ON clienti.id = utenti.cliente_id 
                 WHERE utenti.cliente_id IS NULL";
$stmtClienti = $pdo->prepare($queryClienti);
$stmtClienti->execute();
$clienti = $stmtClienti->fetchAll(PDO::FETCH_ASSOC);

foreach ($clienti as $cliente) {
    $username = generateUsername($cliente['nome']);
    $password = generatePassword($cliente['nome']);
    
    // Verifica se l'utente esiste già
    $queryCheckUser = "SELECT COUNT(*) FROM utenti WHERE username = ?";
    $stmtCheckUser = $pdo->prepare($queryCheckUser);
    $stmtCheckUser->execute([$username]);
    $exists = $stmtCheckUser->fetchColumn();
    
    if ($exists == 0) {
        try {
            // Inserisce un nuovo utente
            $queryInsertUser = "INSERT INTO utenti (username, password, cliente_id, ruolo, password_expired) 
                                VALUES (?, ?, ?, 'user', '1')";
            $stmtInsertUser = $pdo->prepare($queryInsertUser);
            
            // Cripta la password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            $stmtInsertUser->execute([$username, $hashedPassword, $cliente['cliente_id']]);
            
            $output .= "Utente creato: $username - Password: $password\n";
       		} 
	        catch (PDOException $e) {
            $output .= "Errore durante l'inserimento dell'utente: " . $e->getMessage() . "\n";
	        }
		    } 
		else {
        $output .= "L'utente con username $username esiste già.\n";
		}
}

?>
