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
                <button id="menu-toggle" aria-label="Apri menu">☰</button>
                <ul id="menu" class="hidden">
                <?php include 'include/menu.php'; ?>
                </ul>
            </nav>
        </header>
        <aside class="sidebar">
            <nav class="menu">
            	<ul >
               		<?php include 'include/menu.php'; ?>
               	</ul>
            </nav>
        </aside>
		
        <section id="carrello">
                <span>Carrello:</span>
                <span id="totale">€0.00</span>
        </section>
        <section>
            <input type="text" id="search" placeholder="Cerca un prodotto...">
        </section>
        <section id="prodotti-section">
            <div id="prodotti-lista"></div>
        </section>
        <section id="carrello-section">
            <h2>Carrello</h2>
            <button id="checkout">Checkout</button>
            <ul id="carrello-lista"></ul>
        </section>
    </div>
    <script src="include/menu.js"></script>
    <script src="script.js"></script>
    <?php include 'include/footer.php'; ?>
</body>
</html>
