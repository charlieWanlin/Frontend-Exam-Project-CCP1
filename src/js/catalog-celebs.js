// ─────────────────────────────────────────────
// SECTION : Tri — Page célébrités
// Gère le menu de tri pour le catalogue célébrités
// ─────────────────────────────────────────────

import { renderCards } from "./renderCards.js";
import { celebrities } from "./data/celebs.js";
import { outfits } from "./data/outfits.js";

const SORT_LABELS = {
  recents: "Récents",
  "alpha-asc": "A → Z",
  "alpha-desc": "Z → A",
  looks: "Nb de looks",
  populaires: "Populaires",
};

/**
 * Trie le tableau des célébrités selon le critère choisi.
 * @param {Array} data - Tableau des célébrités
 * @param {string} sortKey - Clé de tri
 * @returns {Array} Copie triée
 */
function sortCelebs(data, sortKey) {
  const arr = [...data];
  if (sortKey === "recents") {
    return arr.reverse();
  }
  if (sortKey === "alpha-asc") {
    return arr.sort((a, b) => (a.name || "").localeCompare(b.name || ""));
  }
  if (sortKey === "alpha-desc") {
    return arr.sort((a, b) => (b.name || "").localeCompare(a.name || ""));
  }
  if (sortKey === "looks") {
    return arr.sort((a, b) => (b.looks ?? 0) - (a.looks ?? 0));
  }
  return arr;
}

export const initCelebSort = () => {
  const btn = document.getElementById("sort-btn");
  const btnMobile = document.getElementById("sort-btn-mobile");
  const menu = document.getElementById("sort-menu");
  const menuMobile = document.getElementById("sort-menu-mobile");
  const sortOpts = document.querySelectorAll(".sort-opt");

  if (!btn || !menu) {
    return;
  }

  let celebOffset = 16;

  const applySortAndRender = (sorted) => {
    const toShow = sorted.slice(0, celebOffset);
    renderCards(".celebrities-grid", toShow, "celebrity", outfits);
    const countEl = document.querySelector("#celebs-count");
    if (countEl) {
      countEl.textContent = Math.min(celebOffset, sorted.length);
    }
    const loadBtn = document.querySelector("#load-more-celebs");
    if (loadBtn) {
      loadBtn.style.display = celebOffset >= sorted.length ? "none" : "";
    }
  };

  // Bouton Charger plus
  const loadBtn = document.querySelector("#load-more-celebs");
  if (loadBtn) {
    loadBtn.addEventListener("click", () => {
      const sortBtn = document.getElementById("sort-btn");
      const sortKey = sortBtn?.dataset?.currentSort || "populaires";
      const sorted = sortCelebs(celebrities, sortKey);
      celebOffset += 16;
      applySortAndRender(sorted);
    });
  }

  btn.addEventListener("click", (e) => {
    e.stopPropagation();
    menu.classList.toggle("hidden");
    if (menuMobile) {
      menuMobile.classList.add("hidden");
    }
  });

  if (btnMobile) {
    btnMobile.addEventListener("click", (e) => {
      e.stopPropagation();
      if (menuMobile) {
        menuMobile.classList.toggle("hidden");
      }
      menu.classList.add("hidden");
    });
  }

  document.addEventListener("click", () => {
    menu.classList.add("hidden");
    if (menuMobile) {
      menuMobile.classList.add("hidden");
    }
  });

  sortOpts.forEach((opt) => {
    opt.addEventListener("click", (e) => {
      e.stopPropagation();
      const key = opt.dataset.sort;
      let label;
      if (key && SORT_LABELS[key]) {
        label = SORT_LABELS[key];
      } else {
        label = opt.textContent.trim();
      }
      const labelEl = btn.querySelector("#sort-label");
      if (labelEl) {
        labelEl.textContent = label;
      }
      const labelMobile = btnMobile?.querySelector("#sort-label-mobile");
      if (labelMobile) {
        labelMobile.textContent = label;
      }
      menu.classList.add("hidden");
      if (menuMobile) {
        menuMobile.classList.add("hidden");
      }
      if (key) {
        const sortBtn = document.getElementById("sort-btn");
        if (sortBtn) {
          sortBtn.dataset.currentSort = key;
        }
        const sorted = sortCelebs(celebrities, key);
        applySortAndRender(sorted);
      }
    });
  });
};
