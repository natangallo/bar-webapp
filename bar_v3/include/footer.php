<?php
// include/footer.php
$page = basename($_SERVER['PHP_SELF']); // Otteniamo il nome della pagina corrente

?>

<footer class="<?php echo $page === 'accesso.php' ? 'login-footer' : 'regular-footer'; ?>">
    <div class="footer-content">
        <img src="https://centroemmaus.it/wp-content/uploads/2014/05/logo-Arca-footer2.png" alt="Emmaus Logo" class="footer-logo">

        <!-- Disclaimer e Copyright -->
        <p>&copy; <?php echo date('Y'); ?> Emmaus Bar App. Tutti i diritti riservati.</p>

        <!-- Nav per pagine diverse dalla pagina di accesso -->
        <?php if ($page !== 'accesso.php'): ?>
            <nav class="footer-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo BASE_URL; ?>index.php" class="footer-link">
                        <span class="footer-icon">&#8962;</span> Home
                    </a>
                    <a href="<?php echo BASE_URL; ?>accesso/logout.php" class="footer-link">Logout</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>
</footer>
