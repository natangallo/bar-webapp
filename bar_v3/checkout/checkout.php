<?php
// checkout.php

// Includi il file per la gestione della sessione
include '../accesso/session.php';

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestione Clienti</h1>
            <nav>
                <button id="menu-toggle" aria-label="Apri menu">☰</button>
                <ul id="menu" class="hidden">
                <?php include '../include/menu.php'; ?>
                </ul>
            </nav>
        </header>
        
        <aside class="sidebar">
            <nav class="menu">
            	<ul >
               		<?php include '../include/menu.php'; ?>
               	</ul>
            </nav>
        </aside>
        
        <section id="checkout-section">
            <h2>Riepilogo Acquisti</h2>
            <div id="carrello-riepilogo"></div>
            <h2>Informazioni Cliente</h2>
            <input type="text" id="cerca-cliente" placeholder="Cerca cliente...">
            <div id="clienti-lista"></div>

        </section>
    </div>

    <script src="checkout.js"></script>
    <?php include '../include/footer.php'; ?>
</body>
</html>
