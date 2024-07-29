document.addEventListener('DOMContentLoaded', function () {
    const productList = document.getElementById('product-list');
    const cartItems = document.getElementById('cart-items');
    const checkoutButton = document.getElementById('checkout-button');
    const cartCode = document.getElementById('cart-code');

    let cart = [];

    function fetchProducts() {
        fetch('api/get_products.php')
            .then(response => response.json())
            .then(products => {
                const ul = document.createElement('ul');
                products.forEach(product => {
                    const li = document.createElement('li');
                    li.textContent = `${product.name} - $${product.price} (In stock: ${product.stock_quantity})`;
                    const button = document.createElement('button');
                    button.textContent = 'Add to Cart';
                    button.addEventListener('click', () => addToCart(product));
                    li.appendChild(button);
                    ul.appendChild(li);
                });
                productList.appendChild(ul);
            });
    }

    function addToCart(product) {
        const existingItem = cart.find(item => item.id === product.id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ ...product, quantity: 1 });
        }
        renderCart();
    }

    function renderCart() {
        cartItems.innerHTML = '';
        cart.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.name} - $${item.price} x ${item.quantity}`;
            cartItems.appendChild(li);
        });
    }

    checkoutButton.addEventListener('click', () => {
        fetch('api/create_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ items: cart.map(item => ({ product_id: item.id, quantity: item.quantity })) })
        })
            .then(response => response.json())
            .then(data => {
                cartCode.textContent = `Your cart code: ${data.cartId}`;
            });
    });

    fetchProducts();
});
