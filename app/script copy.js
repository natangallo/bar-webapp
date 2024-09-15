document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');
    const prodottiLista = document.getElementById('prodotti-lista');
    const carrelloLista = document.getElementById('carrello-lista');
    const totaleElement = document.getElementById('totale');
    const checkoutButton = document.getElementById('checkout');
    
    let carrello = [];

    // Funzione per caricare i prodotti dal database
    function caricaProdotti() {
        fetch('prodotti/prodotti.php')
            .then(response => response.json())
            .then(prodotti => {
                prodotti.forEach(prodotto => {
                    const item = creaElementoProdotto(prodotto);
                    prodottiLista.appendChild(item);
                });
            });
    }

    // Funzione per creare l'elemento del prodotto
    function creaElementoProdotto(prodotto) {
        const div = document.createElement('div');
        div.className = 'product-item';
        div.innerHTML = `
            <span>${prodotto.nome} - €${prodotto.prezzo}</span>
            <button data-id="${prodotto.id}" data-prezzo="${prodotto.prezzo}">Aggiungi</button>
        `;
        div.querySelector('button').addEventListener('click', aggiungiAlCarrello);
        return div;
    }

    // Funzione per aggiungere un prodotto al carrello
    function aggiungiAlCarrello(e) {
        const prodottoId = e.target.getAttribute('data-id');
        const prodottoPrezzo = parseFloat(e.target.getAttribute('data-prezzo'));
        const prodottoNome = e.target.previousElementSibling.textContent.split(' - ')[0];

        const item = carrello.find(item => item.id == prodottoId);
        if (item) {
            item.quantita++;
        } else {
            carrello.push({ id: prodottoId, nome: prodottoNome, prezzo: prodottoPrezzo, quantita: 1 });
        }

        aggiornaCarrello();
    }

    // Funzione per aggiornare la lista del carrello
    function aggiornaCarrello() {
        carrelloLista.innerHTML = '';
        let totale = 0;

        carrello.forEach(item => {
            totale += item.prezzo * item.quantita;

            const li = document.createElement('li');
            li.className = 'carrello-item';
            li.innerHTML = `
                <span>${item.nome} x${item.quantita} - €${(item.prezzo * item.quantita).toFixed(2)}</span>
                <div>
                    <button class="increase" data-id="${item.id}">+</button>
                    <button class="decrease" data-id="${item.id}">-</button>
                </div>
            `;
            li.querySelector('.increase').addEventListener('click', aumentaQuantita);
            li.querySelector('.decrease').addEventListener('click', diminuisciQuantita);
            carrelloLista.appendChild(li);
        });

        totaleElement.textContent = `€${totale.toFixed(2)}`;
    }

    // Funzione per aumentare la quantità di un prodotto nel carrello
    function aumentaQuantita(e) {
        const prodottoId = e.target.getAttribute('data-id');
        const item = carrello.find(item => item.id == prodottoId);
        item.quantita++;
        aggiornaCarrello();
    }

    // Funzione per diminuire la quantità di un prodotto nel carrello
    function diminuisciQuantita(e) {
        const prodottoId = e.target.getAttribute('data-id');
        const item = carrello.find(item => item.id == prodottoId);
        if (item.quantita > 1) {
            item.quantita--;
        } else {
            carrello = carrello.filter(item => item.id != prodottoId);
        }
        aggiornaCarrello();
    }

    // Funzione per filtrare i prodotti durante la digitazione
    searchInput.addEventListener('input', function () {
        const query = searchInput.value.toLowerCase();
        const items = prodottiLista.querySelectorAll('.product-item');
        items.forEach(item => {
            const nome = item.textContent.toLowerCase();
            if (nome.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    caricaProdotti();
    
    /// Gestire il click del pulsante Checkout
    document.getElementById('checkout').addEventListener('click', function () {
        // Memorizzare i dati del carrello in sessionStorage
        sessionStorage.setItem('carrello', JSON.stringify(carrello));
        sessionStorage.setItem('totale', totaleElement.textContent);

        // Reindirizzare alla pagina di checkout
        window.location.href = 'checkout/checkout.php';
    });
    
});
