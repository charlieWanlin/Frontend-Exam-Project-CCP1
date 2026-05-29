// ─────────────────────────────────────────────
// SECTION : Module Panier
// Gère l'ajout, la suppression, la mise à jour des articles
// et la persistance via localStorage. Met à jour le badge panier.
// ─────────────────────────────────────────────

const CART_STORAGE_KEY = "le-dressing-cart";

/**
 * Récupère le panier depuis localStorage.
 * Si la clé n'existe pas ou est invalide, retourne un tableau vide.
 * @returns {Array} Tableau des articles du panier
 */
export function getCart() {
  try {
    const stored = localStorage.getItem(CART_STORAGE_KEY);
    if (!stored) {
      return [];
    }
    const parsed = JSON.parse(stored);
    if (!Array.isArray(parsed)) {
      return [];
    }
    return parsed;
  } catch (err) {
    console.warn("Erreur lecture panier:", err);
    return [];
  }
}

/**
 * Enregistre le panier dans localStorage et met à jour le badge.
 * @param {Array} cart - Tableau des articles du panier
 */
function saveCart(cart) {
  try {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
    updateCartBadge();
  } catch (err) {
    console.warn("Erreur sauvegarde panier:", err);
  }
}

/**
 * Calcule le nombre total d'articles (quantités cumulées) dans le panier.
 * @param {Array} cart - Tableau des articles
 * @returns {number}
 */
function getCartItemCount(cart) {
  let total = 0;
  for (let i = 0; i < cart.length; i++) {
    const qty = cart[i].quantity;
    if (typeof qty === "number" && qty > 0) {
      total += qty;
    } else {
      total += 1;
    }
  }
  return total;
}

/**
 * Met à jour le badge du panier dans la navbar.
 * Cherche tous les éléments .cart-page et ajoute/retire le badge.
 */
export function updateCartBadge() {
  const cart = getCart();
  const count = getCartItemCount(cart);
  const links = document.querySelectorAll(".cart-page");

  links.forEach((link) => {
    let badge = link.querySelector(".cart-badge");
    if (count > 0) {
      if (!badge) {
        badge = document.createElement("span");
        badge.className =
          "cart-badge absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] flex items-center justify-center bg-gray-900 text-white text-[10px] font-medium rounded-full px-1";
        badge.setAttribute("aria-label", `${count} article(s) dans le panier`);
        link.style.position = "relative";
        link.appendChild(badge);
      }
      badge.textContent = count > 99 ? "99+" : count;
    } else {
      if (badge) {
        badge.remove();
      }
    }
  });
}

/**
 * Ajoute un article au panier (avec taille et couleur).
 * Si le même article (id + taille + couleur) existe déjà, on incrémente la quantité.
 * @param {Object} item - { id, name, price, img, size, color }
 * @param {number} quantity - Quantité à ajouter
 */
export function addToCart(item, quantity = 1) {
  if (!item || !item.id) {
    console.warn("addToCart: article invalide");
    return;
  }

  const cart = getCart();
  const size = item.size ?? "";
  const color = item.color ?? "";
  let found = false;

  for (let i = 0; i < cart.length; i++) {
    if (
      cart[i].id === item.id &&
      (cart[i].size ?? "") === size &&
      (cart[i].color ?? "") === color
    ) {
      cart[i].quantity = (cart[i].quantity || 1) + quantity;
      found = true;
      break;
    }
  }

  if (!found) {
    cart.push({
      id: item.id,
      name: item.name,
      price: item.price,
      img: item.img ?? "",
      size: size,
      color: color,
      quantity: quantity,
    });
  }

  saveCart(cart);
}

/**
 * Met à jour la quantité d'un article à l'index donné.
 * Si la quantité est <= 0, l'article est supprimé.
 * @param {number} index - Index de l'article dans le panier
 * @param {number} quantity - Nouvelle quantité
 */
export function updateCartItemQuantity(index, quantity) {
  const cart = getCart();
  if (index < 0 || index >= cart.length) {
    return;
  }
  if (quantity <= 0) {
    cart.splice(index, 1);
  } else {
    cart[index].quantity = quantity;
  }
  saveCart(cart);
}

/**
 * Supprime un article du panier à l'index donné.
 * @param {number} index - Index de l'article
 */
export function removeFromCart(index) {
  const cart = getCart();
  if (index < 0 || index >= cart.length) {
    return;
  }
  cart.splice(index, 1);
  saveCart(cart);
}

/**
 * Vide entièrement le panier.
 */
export function clearCart() {
  saveCart([]);
}

/**
 * Calcule le sous-total TTC du panier.
 * @returns {number}
 */
export function getCartSubtotal() {
  const cart = getCart();
  let total = 0;
  for (let i = 0; i < cart.length; i++) {
    const p = cart[i].price;
    const q = cart[i].quantity ?? 1;
    if (typeof p === "number" && p >= 0) {
      total += p * q;
    }
  }
  return total;
}
