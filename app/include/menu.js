// menu.js

document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');

    if (menuToggle && menu) {
        // Gestione del menu burger
        menuToggle.addEventListener('click', function () {
            menu.classList.toggle('show');
        });
    }
});
