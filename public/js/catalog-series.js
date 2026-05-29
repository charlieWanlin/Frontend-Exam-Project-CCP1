// ─────────────────────────────────────────────
// SECTION : Tri et pagination — Page séries
// Gère le menu de tri et le bouton "Charger plus"
// ─────────────────────────────────────────────

import { renderCards } from "./renderCards.js";
import { series } from "./data/series.js";
import { initFilter } from "./filter.js";

const SORT_LABELS = {
  recents: "Récents",
  "alpha-asc": "A → Z",
  "alpha-desc": "Z → A",
  looks: "Nb de looks",
  populaires: "Populaires",
};

/**
 * Trie le tableau des séries selon le critère choisi.
 * @param {Array} data - Tableau des séries
 * @param {string} sortKey - Clé de tri (recents, alpha-asc, alpha-desc, looks, populaires)
 * @returns {Array} Copie triée
 */
function sortSeriesData(data, sortKey) {
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

export const initSort = () => {
  const btn = document.getElementById("sort-btn");
  const btnMobile = document.getElementById("sort-btn-mobile");
  const menu = document.getElementById("sort-menu");
  const menuMobile = document.getElementById("sort-menu-mobile");
  const sortOpts = document.querySelectorAll(".sort-opt");

  if (!btn || !menu) {
    return;
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
      const labelEl = btn.querySelector("#sort-label");
      const labelMobile = btnMobile?.querySelector("#sort-label-mobile");
      let label;
      if (key && SORT_LABELS[key]) {
        label = SORT_LABELS[key];
      } else {
        label = opt.textContent.trim();
      }
      if (labelEl) {
        labelEl.textContent = label;
      }
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
        const sorted = sortSeriesData(series, key);
        const toShow = sorted.slice(0, seriesOffset);
        renderCards(".series-grid", toShow, "serie");
        const countEl = document.querySelector("#series-count");
        if (countEl) {
          countEl.textContent = Math.min(seriesOffset, sorted.length);
        }
        const loadBtn = document.querySelector("#load-more");
        if (loadBtn) {
          loadBtn.style.display = seriesOffset >= series.length ? "none" : "";
        }
      }
    });
  });
};

export let seriesOffset = 16;
export const loadMore = () => {
  const loadBtn = document.querySelector("#load-more");
  let seriesCount = document.querySelector("#series-count");
  seriesCount.textContent = seriesOffset;
  loadBtn.addEventListener("click", () => {
    seriesOffset += 16; // ← Incrémente d'abord
    seriesCount.textContent = seriesOffset;

    renderCards(
      ".series-grid",
      series.slice(0, seriesOffset), // ← Puis utilise directement
      "serie",
    );

    if (seriesOffset >= series.length) {
      loadBtn.style.display = "none";
    }
  });
};
