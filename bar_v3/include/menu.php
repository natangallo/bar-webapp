<?php
// define('BASE_URL', 'http://192.168.178.195:8888/cassabar/'); // Modifica questo URL con il percorso assoluto del tuo sito
// define('BASE_URL', 'http://localhost:8888/cassabar/');
?>

<button id="menu-toggle" aria-label="Apri menu">☰</button>
<ul id="menu" class="hidden">
    <li><a href="<?php echo BASE_URL; ?>index.php">Home</a></li>
    <li><a href="<?php echo BASE_URL; ?>prodotti/gestione_prodotti.php">Prodotti</a></li>
    <li><a href="<?php echo BASE_URL; ?>transazioni/gestione_transazioni.php">Transazioni</a></li>
    <li><a href="<?php echo BASE_URL; ?>clienti/gestione_clienti.php">Clienti</a></li>
</ul>
