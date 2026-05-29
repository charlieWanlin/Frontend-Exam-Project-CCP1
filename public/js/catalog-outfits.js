// ─────────────────────────────────────────────
// SECTION : Tri — Page outfits (looks d'une célébrité)
// Gère le menu de tri pour la grille des looks
// ─────────────────────────────────────────────

import { renderOutfitCards } from "./renderCards.js";

const SORT_LABELS = {
  date: "Par date",
  popularite: "Par popularité",
  evenement: "Par événement",
};

/**
 * Trie les outfits selon le critère choisi.
 * @param {Array} items - Tableau des outfits
 * @param {string} sortKey - Clé de tri
 * @returns {Array}
 */
function sortOutfits(items, sortKey) {
  const arr = [...items];
  if (sortKey === "date") {
    return arr.sort((a, b) => (b.id ?? 0) - (a.id ?? 0));
  }
  if (sortKey === "popularite") {
    return arr.sort((a, b) => (b.id ?? 0) - (a.id ?? 0));
  }
  if (sortKey === "evenement") {
    return arr.sort((a, b) => (a.title || "").localeCompare(b.title || ""));
  }
  return arr;
}

/**
 * Initialise le dropdown de tri sur la page outfits.
 * @param {Array} data - Tableau des outfits
 * @param {string} gridSelector - Sélecteur de la grille
 * @param {string} countSelector - Sélecteur du compteur
 */
export const initOutfitSort = (data, gridSelector, countSelector) => {
  const triBtn = document.getElementById("tri-btn");
  const triMenu = document.getElementById("tri-menu");
  const triOpts = document.querySelectorAll("#tri-menu li[data-sort]");

  if (!triBtn || !triMenu || !data || !data.length) {
    return;
  }

  triBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    triMenu.classList.toggle("hidden");
  });

  document.addEventListener("click", () => {
    triMenu.classList.add("hidden");
  });

  triOpts.forEach((opt) => {
    opt.addEventListener("click", (e) => {
      e.stopPropagation();
      const key = opt.dataset.sort;
      const labelEl = document.getElementById("tri-label");
      if (labelEl && key && SORT_LABELS[key]) {
        labelEl.textContent = SORT_LABELS[key];
      }
      if (triBtn && key) {
        triBtn.dataset.currentSort = key;
      }
      triMenu.classList.add("hidden");
      if (key) {
        const activeFilterBtn = document.querySelector(".outfit-filter-btn.text-gray-900");
        const currentFilter = activeFilterBtn?.dataset?.filter || "tous";
        let toSort = data;
        if (currentFilter !== "tous") {
          toSort = data.filter((item) => {
            if (!item.tags) return false;
            return Array.isArray(item.tags) ? item.tags.includes(currentFilter) : item.tags === currentFilter;
          });
        }
        const sorted = sortOutfits(toSort, key);
        renderOutfitCards(gridSelector, sorted);
        const countEl = countSelector ? document.querySelector(countSelector) : null;
        if (countEl) {
          countEl.textContent = sorted.length;
        }
      }
    });
  });
};
