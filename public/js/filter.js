// ─────────────────────────────────────────────
// SECTION : Filtres et tri — Catalogues
// Gère les boutons de filtre et le tri pour séries et célébrités
// ─────────────────────────────────────────────

import { renderCards, renderOutfitCards } from "./renderCards.js";

/**
 * Applique le tri sur un tableau (séries ou célébrités).
 * @param {Array} items - Tableau à trier
 * @param {string} sortKey - Clé de tri (recents, alpha-asc, alpha-desc, looks, populaires)
 * @returns {Array}
 */
function applySort(items, sortKey) {
  const arr = [...items];
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

export const initFilter = ({
  filterSelector = ".filter-btn",
  defaultFilterValue = "tous",
  gridSelector,
  data,
  cardType,
  countSelector = null,
  loadMoreSelector = null,
  initialOffset = 16,
  outfits = [],
} = {}) => {
  const filterBtns = document.querySelectorAll(filterSelector);
  const defaultBtn = document.querySelector(
    `[data-filter="${defaultFilterValue}"]`,
  );
  let offset = initialOffset;

  if (!filterBtns.length) {
    return;
  }

  if (defaultBtn) {
    defaultBtn.classList.remove("text-gray-400");
    defaultBtn.classList.add(
      "text-gray-900",
      "border",
      "border-gray-400",
      "rounded-lg",
    );
  }

  filterBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      // Reset visuel de tous les boutons
      filterBtns.forEach((b) => {
        b.classList.remove(
          "text-gray-900",
          "border",
          "border-gray-400",
          "rounded-lg",
        );
        b.classList.add("text-gray-400");
      });

      // Active visuellement le bouton cliqué
      e.target.classList.remove("text-gray-400");
      e.target.classList.add(
        "text-gray-900",
        "border",
        "border-gray-400",
        "rounded-lg",
      );

      const styleChoisi = e.target.dataset.filter;

      let loadBtn = null;
      if (loadMoreSelector) {
        loadBtn = document.querySelector(loadMoreSelector);
      }

      let countEl = null;
      if (countSelector) {
        countEl = document.querySelector(countSelector);
      }

      let itemsFiltres;

      if (styleChoisi === "tous") {
        offset = initialOffset;
        let base = data;
        if (cardType === "serie" || cardType === "celebrity") {
          const sortBtn = document.getElementById("sort-btn");
          const sortKey = sortBtn?.dataset?.currentSort || "populaires";
          base = applySort(data, sortKey);
        }
        itemsFiltres = base.slice(0, offset);

        if (countEl) {
          countEl.textContent = Math.min(offset, base.length);
        }

        if (loadBtn) {
          if (offset >= data.length) {
            loadBtn.style.display = "none";
          } else {
            loadBtn.style.display = "";
          }
        }
      } else {
        itemsFiltres = data.filter((item) => {
          if (item.tags) {
            if (Array.isArray(item.tags)) {
              return item.tags.includes(styleChoisi);
            } else {
              return item.tags === styleChoisi;
            }
          }

          if (item.description) {
            if (Array.isArray(item.description)) {
              return item.description.includes(styleChoisi);
            } else {
              return item.description === styleChoisi;
            }
          }

          return false;
        });

        if (countEl) {
          countEl.textContent = itemsFiltres.length;
        }

        if (loadBtn) {
          loadBtn.style.display = "none";
        }
      }

      // Pour les filtres autres que "tous", appliquer le tri sur les items filtrés
      let toRender = itemsFiltres;
      if (styleChoisi !== "tous" && (cardType === "serie" || cardType === "celebrity")) {
        const sortBtn = document.getElementById("sort-btn");
        const sortKey = sortBtn?.dataset?.currentSort || "populaires";
        toRender = applySort(itemsFiltres, sortKey);
      }

      if (cardType === "outfit") {
        renderOutfitCards(gridSelector, toRender);
      } else {
        const extraData = cardType === "celebrity" ? outfits : [];
        renderCards(gridSelector, toRender, cardType, extraData);
      }
    });
  });
};
export const initOutfitFilter = ({ data, gridSelector, countSelector }) => {
  const btns = document.querySelectorAll(".outfit-filter-btn");

  if (!btns.length) {
    return;
  }

  btns.forEach((btn) => {
    btn.addEventListener("click", (e) => {

      // Reset visuel de tous les boutons
      btns.forEach((b) => {
        b.classList.remove("text-gray-900", "border-gray-900");
        b.classList.add("text-gray-400", "border-transparent");
      });

      // Active visuellement le bouton cliqué
      e.target.classList.remove("text-gray-400", "border-transparent");
      e.target.classList.add("text-gray-900", "border-gray-900");

      const filtre = e.target.dataset.filter;
      const countEl = countSelector ? document.querySelector(countSelector) : null;

      let items;

      if (filtre === "tous") {
        items = data;
      } else {
        items = data.filter((item) => {
          if (!item.tags) {
            return false;
          }
          if (Array.isArray(item.tags)) {
            return item.tags.includes(filtre);
          } else {
            return item.tags === filtre;
          }
        });
      }

      if (countEl) {
        countEl.textContent = items.length;
      }

      // Applique le tri si le menu tri existe (page outfits)
      let toRender = items;
      const triBtn = document.getElementById("tri-btn");
      const sortKey = triBtn?.dataset?.currentSort || "date";
      if (triBtn && sortKey) {
        toRender = [...items].sort((a, b) => {
          if (sortKey === "date" || sortKey === "popularite") {
            return (b.id ?? 0) - (a.id ?? 0);
          }
          if (sortKey === "evenement") {
            return (a.title || "").localeCompare(b.title || "");
          }
          return 0;
        });
      }

      renderOutfitCards(gridSelector, toRender);
    });
  });
};