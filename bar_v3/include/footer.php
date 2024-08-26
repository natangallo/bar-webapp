<?php
// define('BASE_URL', 'http://192.168.178.195:8888/cassabar/'); // Modifica questo URL con il percorso assoluto del tuo sito
// define('BASE_URL', 'http://localhost:8888/cassabar/');
?>

<footer>
    <div class="footer-content">
        <p>&copy; <?php echo date('Y'); ?> Emmaus Bar App. Tutti i diritti riservati.</p>
        <nav class="footer-nav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>index.php" class="footer-link">
                    <span class="footer-icon">&#8962;</span> <!-- Codice HTML per un'icona di casa -->
                    Home
                </a>
                <a href="<?php echo BASE_URL; ?>accesso/logout.php" class="footer-link">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</footer>
