<?php
// index.php
// Includi il file per la gestione della sessione
include 'accesso/session.php';

?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Cassa Bar</h1>
            <nav>
                <?php include 'include/menu.php'; ?>
            </nav>
        </header>
        <div id="carrello">
                <span>Carrello:</span>
                <span id="totale">€0.00</span>
        </div>
        <section id="prodotti-section">
            <input type="text" id="search" placeholder="Cerca un prodotto...">
            <div id="prodotti-lista"></div>
        </section>
        <section id="carrello-section">
            <h2>Carrello</h2>
            <ul id="carrello-lista"></ul>
            <button id="checkout">Checkout</button>
        </section>
    </div>
    <script src="include/menu.js"></script>
    <script src="script.js"></script>
    <?php include 'include/footer.php'; ?>
</body>
</html>
