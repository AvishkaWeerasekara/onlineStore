// =============================================
//  main.js — ShopNest JavaScript
//  Handles: Cart, Toast notifications
// =============================================


// ── CART FUNCTIONS ────────────────────────────

// Get cart from browser storage
function getCart() {
    var cart = localStorage.getItem('shopnest_cart');
    if (cart) {
        return JSON.parse(cart);
    }
    return [];
}

// Save cart to browser storage
function saveCart(cart) {
    localStorage.setItem('shopnest_cart', JSON.stringify(cart));
}

// Add item to cart
function addToCart(id, name, price, image) {
    var cart = getCart();

    // Check if item already exists
    var found = false;
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].id == id) {
            cart[i].qty = cart[i].qty + 1;
            found = true;
            break;
        }
    }

    // If not found, add new item
    if (!found) {
        cart.push({
            id: id,
            name: name,
            price: price,
            image: image,
            qty: 1
        });
    }

    saveCart(cart);
    updateCartBadge();
    showToast('Item added to cart!');
}

// Remove item from cart
function removeFromCart(id) {
    var cart = getCart();
    var newCart = [];
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].id != id) {
            newCart.push(cart[i]);
        }
    }
    saveCart(newCart);
    updateCartBadge();
    // Reload cart page if we are on it
    if (document.getElementById('cart-items')) {
        showCartItems();
    }
}

// Change quantity of item
function changeQty(id, change) {
    var cart = getCart();
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].id == id) {
            cart[i].qty = cart[i].qty + change;
            if (cart[i].qty < 1) cart[i].qty = 1;
            break;
        }
    }
    saveCart(cart);
    updateCartBadge();
    showCartItems();
}

// Update the cart number badge in navbar
function updateCartBadge() {
    var cart  = getCart();
    var total = 0;
    for (var i = 0; i < cart.length; i++) {
        total = total + cart[i].qty;
    }
    var badge = document.getElementById('cart-count');
    if (badge) {
        badge.textContent = total;
    }
}

// Show cart items on the cart page
function showCartItems() {
    var cart      = getCart();
    var container = document.getElementById('cart-items');
    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:50px;color:#9090a8">Your cart is empty. <a href="shop.php" style="color:#c9a84c">Go shopping →</a></td></tr>';
        updateTotals(0);
        return;
    }

    var html = '';
    var subtotal = 0;

    for (var i = 0; i < cart.length; i++) {
        var item      = cart[i];
        var itemTotal = item.price * item.qty;
        subtotal      = subtotal + itemTotal;

        html += '<tr>';
        html += '<td><img src="' + item.image + '" class="cart-item-img" alt=""></td>';
        html += '<td style="padding-left:12px;color:#fdf6e3">' + item.name + '</td>';
        html += '<td style="color:#e8c87a;font-size:18px;font-family:serif">$' + parseFloat(item.price).toFixed(2) + '</td>';
        html += '<td>';
        html += '<div style="display:flex;align-items:center;gap:10px">';
        html += '<button onclick="changeQty(' + item.id + ',-1)" style="width:28px;height:28px;border-radius:4px;border:1px solid rgba(201,168,76,0.2);background:none;color:#c9a84c;font-size:16px;cursor:pointer">−</button>';
        html += '<span style="color:#fdf6e3;font-weight:500">' + item.qty + '</span>';
        html += '<button onclick="changeQty(' + item.id + ',1)"  style="width:28px;height:28px;border-radius:4px;border:1px solid rgba(201,168,76,0.2);background:none;color:#c9a84c;font-size:16px;cursor:pointer">+</button>';
        html += '</div>';
        html += '</td>';
        html += '<td style="color:#e8c87a;font-size:18px;font-family:serif">$' + itemTotal.toFixed(2) + '</td>';
        html += '<td><button onclick="removeFromCart(' + item.id + ')" style="background:none;border:none;color:#9090a8;cursor:pointer;font-size:18px" onmouseover="this.style.color=\'#e05252\'" onmouseout="this.style.color=\'#9090a8\'">✕</button></td>';
        html += '</tr>';
    }

    container.innerHTML = html;
    updateTotals(subtotal);
}

// Update total prices in cart summary
function updateTotals(subtotal) {
    var tax   = subtotal * 0.08;
    var total = subtotal + tax;

    var elSub = document.getElementById('subtotal');
    var elTax = document.getElementById('tax');
    var elTot = document.getElementById('total');

    if (elSub) elSub.textContent = '$' + subtotal.toFixed(2);
    if (elTax) elTax.textContent = '$' + tax.toFixed(2);
    if (elTot) elTot.textContent = '$' + total.toFixed(2);
}


// ── TOAST NOTIFICATION ────────────────────────

function showToast(message) {
    var toast = document.getElementById('toast');
    if (!toast) return;
    document.getElementById('toast-msg').textContent = message;
    toast.classList.add('show');
    setTimeout(function() {
        toast.classList.remove('show');
    }, 2500);
}


// ── RUN WHEN PAGE LOADS ───────────────────────

window.onload = function() {
    updateCartBadge();

    // If cart page exists, show cart items
    if (document.getElementById('cart-items')) {
        showCartItems();
    }
};
