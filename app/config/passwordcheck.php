<?php
// La password in chiaro che vuoi verificare
$plaintextPassword = 'gabrielegallo123';

// L'hash della password memorizzato nel database
$hashedPassword = '$2y$10$LjQiPu4dWmKbCqKgNHpujuJJz5nrYwxDYNVLiVCOjnaPs3T2FDJIu';

// Verifica se la password in chiaro corrisponde all'hash
if (password_verify($plaintextPassword, $hashedPassword)) {
    echo "Password valida!";
} else {
    echo "Password non valida.";
}
?>
