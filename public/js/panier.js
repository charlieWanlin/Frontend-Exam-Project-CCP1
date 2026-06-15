/**
 * panier.js — LE DRESSING
 * Gère les interactions du panier en AJAX (quantité ±, retrait, badge navbar)
 */

const Panier = (() => {

  // ── Badge navbar ──────────────────────────────────────────────────
  async function refreshBadge() {
    try {
      const res  = await fetch('/api/panier', { headers: { 'Accept': 'application/json' } });
      const data = await res.json();
      document.querySelectorAll('[data-panier-count]').forEach(el => {
        el.textContent = data.count;
        el.hidden      = data.count === 0;
      });
    } catch (e) {
      console.warn('Panier badge refresh failed', e);
    }
  }

  // ── Mise à jour du récap (sous-total / livraison / total) ─────────
  function updateSummary(data) {
    const fmt = n => n.toFixed(2).replace('.', ',') + ' €';

    document.getElementById('js-sous-total')?.replaceChildren(
      document.createTextNode(fmt(data.sousTotal))
    );
    document.getElementById('js-total')?.replaceChildren(
      document.createTextNode(fmt(data.total))
    );

    const livraisonEl = document.getElementById('js-livraison');
    if (livraisonEl) {
      if (data.livraison === 0) {
        livraisonEl.innerHTML = '<span class="tag-free">Offerte</span>';
      } else {
        livraisonEl.textContent = fmt(data.livraison);
      }
    }

    // Hint livraison offerte
    const hint = document.querySelector('.summary-shipping-hint');
    if (hint) {
      if (data.sousTotal >= 80) {
        hint.hidden = true;
      } else {
        const remain = (80 - data.sousTotal).toFixed(2).replace('.', ',');
        hint.textContent = `Plus que ${remain} € pour la livraison offerte.`;
        hint.hidden = false;
      }
    }
  }

  // ── Envoi AJAX générique ──────────────────────────────────────────
  async function post(url, body) {
    const fd = new FormData();
    Object.entries(body).forEach(([k, v]) => fd.append(k, v));
    const res = await fetch(url, {
      method:  'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body:    fd,
    });
    return res.json();
  }

  // ── Quantité ± ────────────────────────────────────────────────────
  function bindQuantity() {
    document.addEventListener('click', async e => {
      const btn  = e.target.closest('.js-qty-minus, .js-qty-plus');
      if (!btn) return;

      const form     = btn.closest('.js-qty-form');
      const input    = form.querySelector('.js-qty-input');
      const article  = form.closest('.panier-item');
      const itemId   = article.dataset.itemId;
      const prix     = parseFloat(
        article.querySelector('.panier-item__prix')?.textContent.replace(',', '.') ?? 0
      );

      let qty = parseInt(input.value, 10) || 0;
      qty += btn.classList.contains('js-qty-plus') ? 1 : -1;
      qty  = Math.max(0, qty);
      input.value = qty;

      const data = await post('/api/panier/modifier', { item_id: itemId, quantite: qty });

      if (qty === 0) {
        article.remove();
        checkEmpty();
      } else {
        const sub = article.querySelector('.js-item-subtotal');
        if (sub) sub.textContent = (prix * qty).toFixed(2).replace('.', ',') + ' €';
      }

      updateSummary(data);
      refreshBadge();
    });

    // Validation directe dans l'input
    document.addEventListener('change', async e => {
      const input = e.target.closest('.js-qty-input');
      if (!input) return;

      const form    = input.closest('.js-qty-form');
      const article = form.closest('.panier-item');
      const itemId  = article.dataset.itemId;
      let   qty     = Math.max(0, parseInt(input.value, 10) || 0);
      input.value   = qty;

      const data = await post('/api/panier/modifier', { item_id: itemId, quantite: qty });

      if (qty === 0) {
        article.remove();
        checkEmpty();
      }

      updateSummary(data);
      refreshBadge();
    });
  }

  // ── Retirer ───────────────────────────────────────────────────────
  function bindRetirer() {
    document.addEventListener('submit', async e => {
      const form = e.target.closest('.js-remove-form');
      if (!form) return;
      e.preventDefault();

      const article = form.closest('.panier-item');
      const itemId  = form.querySelector('[name="item_id"]')?.value;

      article.classList.add('removing');

      const data = await post('/api/panier/retirer', { item_id: itemId });

      article.addEventListener('transitionend', () => {
        article.remove();
        checkEmpty();
        updateSummary(data);
        refreshBadge();
      }, { once: true });
    });
  }

  // ── Panier vide (reload propre) ───────────────────────────────────
  function checkEmpty() {
    const remaining = document.querySelectorAll('.panier-item');
    if (remaining.length === 0) {
      // Reload pour afficher l'état vide géré côté PHP
      window.location.reload();
    }
  }

  // ── Flash auto-hide ───────────────────────────────────────────────
  function autoHideFlash() {
    const flash = document.getElementById('js-flash');
    if (!flash) return;
    setTimeout(() => flash.classList.add('flash--hidden'), 3000);
  }

  // ── Init ──────────────────────────────────────────────────────────
  function init() {
    bindQuantity();
    bindRetirer();
    autoHideFlash();
    refreshBadge();
  }

  return { init, refreshBadge };
})();

document.addEventListener('DOMContentLoaded', Panier.init);