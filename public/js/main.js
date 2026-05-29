// On importe les modules
import {
  renderCards,
  renderOutfitCards,
  renderCelebHero,
} from "./renderCards.js";
import { celebrities } from "./data/celebs.js";
import { series } from "./data/series.js";
import { outfits } from "./data/outfits.js";
import { navScroll } from "./navScroll.js";
import { updateCartBadge } from "./cart.js";
import { initSort, seriesOffset, loadMore } from "./catalog-series.js";
import { initCelebSort } from "./catalog-celebs.js";
import { initOutfitSort } from "./catalog-outfits.js";
import { initFilter, initOutfitFilter } from "./filter.js";
import { initSearch } from "./search.js";
import { initCompte } from "./compte.js";
import { initUI } from "./ui.js";

// 
document.addEventListener("DOMContentLoaded", () => {
  // On détecte sur quelle page on est grâce aux data-page dans le HTML
  const isSeriesPage = document.querySelector("[data-page='series-grid']");
  const isCelebritiesPage = document.querySelector(
    "[data-page='celebrities-grid']",
  );
  const isOutfitsPage = document.querySelector("[data-page='outfits-grid']");

  // ══════════════════════════════════════════
  // PAGE SÉRIES
  // ══════════════════════════════════════════
  if (isSeriesPage) {
    // On affiche les premières séries selon l'offset défini dans catalog-series.js
    renderCards(".series-grid", series.slice(0, seriesOffset), "serie");

    // On affiche le nombre total de séries dans le compteur
    const seriesCount = document.querySelector("#series-count");
    if (seriesCount) {
      seriesCount.textContent = series.length;
    }

    // On initialise le tri, le bouton charger plus et les filtres
    initSort();
    loadMore();
    initFilter({
      gridSelector: ".series-grid",
      data: series,
      cardType: "serie",
      countSelector: "#series-count",
      loadMoreSelector: "#load-more",
    });
  } else {
    // Si on n'est pas sur la page séries, on affiche juste 4 séries (ex: page d'accueil)
    renderCards(".series-grid", series.slice(0, 4), "serie");
  }

  // ══════════════════════════════════════════
  // PAGE CÉLÉBRITÉS
  // ══════════════════════════════════════════
  if (isCelebritiesPage) {
    // On affiche les 16 premières célébrités au chargement
    renderCards(".celebrities-grid", celebrities.slice(0, 16), "celebrity", outfits);

    // On affiche le nombre réel de célébrités (initialement 16 affichées)
    const celebsCount = document.querySelector("#celebs-count");
    if (celebsCount) {
      celebsCount.textContent = Math.min(16, celebrities.length);
    }

    // On initialise le tri et les filtres avec le bouton charger plus
    initCelebSort();
    initFilter({
      gridSelector: ".celebrities-grid",
      data: celebrities,
      cardType: "celebrity",
      countSelector: "#celebs-count",
      loadMoreSelector: "#load-more-celebs",
      outfits: outfits,
    });
  } else {
    // Si on n'est pas sur la page célébrités, on affiche juste 4 célébrités (ex: page d'accueil)
    renderCards(".celebrities-grid", celebrities.slice(0, 4), "celebrity", outfits);
  }

  // ══════════════════════════════════════════
  // PAGE OUTFITS D'UNE CÉLÉBRITÉ
  // ══════════════════════════════════════════
  if (isOutfitsPage) {
    // On récupère le slug de la célébrité depuis l'URL (ex: ?celeb=sydney-sweeney)
    const urlParams = new URLSearchParams(window.location.search);
    const celebSlug = urlParams.get("celeb");

    // On cherche la célébrité correspondante dans les données
    const celeb = celebrities.find((c) => c.slug === celebSlug);

    let outfitsDeLaStar;

    if (celebSlug && celeb) {
      // On filtre les outfits pour n'avoir que ceux de la célébrité concernée
      outfitsDeLaStar = outfits.filter((o) => o.celeb === celebSlug);

      // On génère la hero section avec les infos de la célébrité
      renderCelebHero(celeb, outfits);
    } else {
      // Si pas de slug ou célébrité inconnue, on redirige vers la page célébrités
      window.location.href = "catalog-celebrites.html";
      return;
    }

    // On affiche les outfits de la star dans la grille
    renderOutfitCards(".outfits-grid", outfitsDeLaStar);

    // On met à jour le compteur avec le nombre d'outfits de la star
    const outfitsCount = document.querySelector("#outfits-count");
    if (outfitsCount) {
      outfitsCount.textContent = outfitsDeLaStar.length;
    }

    // On initialise les filtres et le tri
    initOutfitFilter({
      data: outfitsDeLaStar,
      gridSelector: ".outfits-grid",
      countSelector: "#outfits-count",
    });
    initOutfitSort(outfitsDeLaStar, ".outfits-grid", "#outfits-count");
  }

  // ══════════════════════════════════════════
  // MODULES GLOBAUX (toutes les pages)
  // ══════════════════════════════════════════
  navScroll();
  initSearch();
  initCompte();
  initUI();
  updateCartBadge();
});
