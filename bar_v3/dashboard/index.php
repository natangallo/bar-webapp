<?php
// dashboard/utenti.php
include '../accesso/session.php';

// Recupera l'ID cliente dalla query string
$clienteId = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
// echo 'User ID: ' . $clienteId;

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utente</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Benvenuto, <!-- Il nome dell'utente sarà inserito qui da JavaScript -->!</h1>
        </header>
        <main>
            <!-- Mostra il saldo -->
            <section id="saldo">
                <h2>Questo è il tuo saldo:</h2>
                <p id="saldo-valore">€0.00</p>
            </section>

            <!-- Grafico a torta -->
            <section id="grafico-prodotti">
                <h2>Statistiche Prodotti Acquistati</h2>
                <canvas id="pie-chart"></canvas>
            </section>

            <!-- Tabella delle transazioni -->
            <section id="transazioni">
                <h2>Transazioni</h2>
                <table id="transazioni-table">
                    <thead>
                        <tr>
                            <th>Data e Ora</th>
                            <th>Totale</th>
                            <th>Descrizione</th>
                            <th>Dettagli</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Le righe verranno inserite dinamicamente con JavaScript -->
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <!-- Script per il grafico e per il pulsante di espansione -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="utenti.js"></script>
    <?php include '../include/footer.php'; ?>
</body>
</html>
<?php
?>