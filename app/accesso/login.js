document.getElementById('login-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username, password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else if (data.password_expired) {
            // Mostra il modulo per il cambio password
            showChangePasswordForm(username);
        } else {
            document.getElementById('error-message').textContent = data.message;
        }
    });
});

function showChangePasswordForm(username) {
    const formContainer = document.getElementById('login-container');
    formContainer.innerHTML = `
        <form id="change-password-form">
            <h2>Cambia la tua password</h2>
            <input type="hidden" id="username" name="username" value="${username}">
            
            <label for="new-password">Nuova Password:</label>
            <input type="password" id="new-password" name="new-password" required>
            
            <label for="confirm-password">Conferma Nuova Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
            
            <button type="submit">Cambia Password</button>
            <p id="error-message"></p>
        </form>
    `;

    document.getElementById('change-password-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (newPassword !== confirmPassword) {
            document.getElementById('error-message').textContent = 'Le password non corrispondono';
            return;
        }

        fetch('change_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, newPassword })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('error-message').textContent = 'Password cambiata con successo. Ora puoi accedere.';
                // Eventuale redirect alla pagina di login o auto-login
                setTimeout(() => {
                    window.location.href = 'accesso.php';
                }, 2000);
            } else {
                document.getElementById('error-message').textContent = data.message;
            }
        });
    });
}
