<?php
// gestione_transazioni.phop
// Includi il file per la gestione della sessione
include '../accesso/session.php';

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transazioni</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Transazioni</h1>
            <nav>
                <?php include '../include/menu.php'; ?>
            </nav>
        </header>
        <input type="text" id="search-transazioni" placeholder="Cerca per nome cliente...">
        <table id="transazioni-table">
            <thead>
                <tr>
                    <th data-column="nome" data-order="desc">Nome Cliente</th>
                    <th data-column="totale" data-order="desc">Totale</th>
                    <th data-column="data" data-order="desc">Data e Ora</th>
                    <th>Dettagli</th> <!-- Nuova colonna per il pulsante -->
                </tr>
            </thead>
            <tbody>
                <!-- I dati verranno inseriti dinamicamente tramite JS -->
            </tbody>
        </table>
    </div>
    <script src="transazioni.js"></script>
    <?php include '../include/footer.php'; ?>
</body>
</html>
