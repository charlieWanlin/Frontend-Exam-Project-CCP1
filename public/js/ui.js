// ─────────────────────────────────────────────
// SECTION : UI — Navbar, modals, menu mobile, filtres mobile
// Gère l'ouverture/fermeture du menu mobile et du panneau de filtres
// ─────────────────────────────────────────────

import { openOverlay, closeOverlay, registerCloseHandler } from "./overlay.js";

/**
 * Initialise le menu mobile (hamburger).
 * Ouvre le drawer au clic, ferme au clic sur l'overlay.
 */
function initMobileMenu() {
  const btn = document.getElementById("mobile-menu-btn");
  const menu = document.getElementById("mobile-menu");

  if (!btn || !menu) {
    return;
  }

  const open = () => {
    menu.classList.remove("hidden");
    menu.classList.add("flex");
    openOverlay();
  };

  const close = () => {
    menu.classList.add("hidden");
    menu.classList.remove("flex");
  };

  registerCloseHandler(close);
  btn.addEventListener("click", (e) => {
    e.stopPropagation();
    open();
  });
}

/**
 * Initialise le bouton "Filtres" sur mobile pour afficher le panneau de filtres.
 */
function initFilterToggle() {
  const toggle = document.getElementById("filter-toggle");
  const panel = document.getElementById("filter-menu");

  if (!toggle || !panel) {
    return;
  }

  toggle.addEventListener("click", () => {
    const isHidden = panel.classList.contains("hidden");
    if (isHidden) {
      panel.classList.remove("hidden");
    } else {
      panel.classList.add("hidden");
    }
  });
}

/**
 * Point d'entrée : initialise tous les composants UI.
 */
export function initUI() {
  initMobileMenu();
  initFilterToggle();
}
