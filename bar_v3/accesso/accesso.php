<?php
// accesso.php
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div id="login-container">
        <form id="login-form" method="POST">
            <h2>Benvenuto!</h2>
            <h3>Inserisci le tue credenziali per accedere.</h3>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Accedi</button>
            <p id="error-message"></p>
        </form>
    </div>

    <script src="login.js"></script>
    <?php include '../include/footer.php'; ?>
</body>
</html>
