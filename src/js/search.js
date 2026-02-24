import { series } from "./data/series.js";
import { openOverlay, closeOverlay, registerCloseHandler } from "./overlay.js";

export const initSearch = () => {
  // --- Sélection des éléments du DOM ---
  const trigger = document.getElementById("search-btn");
  const sidebar = document.getElementById("search-sidebar");
  const closeBtn = document.getElementById("search-close");
  const input = document.querySelector("#search-input");
  const suggestions = document.getElementById("search-suggestions");

  // Si les éléments essentiels sont absents, on stoppe l'initialisation
  if (!trigger || !sidebar) return;

  // --- Ouverture / Fermeture de la sidebar ---

  // Ferme la sidebar : la fait glisser hors de l'écran et réinitialise la recherche
  const close = () => {
    sidebar.classList.add("translate-x-full");
    input.value = "";
    suggestions.innerHTML = "";
  };

  // Ouvre la sidebar et affiche l'overlay en arrière-plan
  const open = () => {
    sidebar.classList.remove("translate-x-full");
    openOverlay();
  };

  registerCloseHandler(close); // Enregistre la fermeture auprès du gestionnaire global d'overlay
  trigger.addEventListener("click", (e) => {
    e.stopPropagation(); // Empêche la propagation pour ne pas déclencher d'autres listeners
    open();
  });
  closeBtn.addEventListener("click", closeOverlay);

  // --- Recherche en temps réel ---
  input.addEventListener("input", (e) => {
    const query = e.target.value.toLowerCase();
    suggestions.innerHTML = ""; // Réinitialise les suggestions à chaque frappe

    // On attend au moins 2 caractères avant de lancer la recherche
    if (query.length < 2) return;

    // Filtre les séries dont le nom contient la saisie, puis affiche chaque résultat
    series
      .filter((serie) => serie.name.toLowerCase().includes(query))
      .forEach((serie) => {
        const card = document.createElement("div");
        card.className =
          "flex items-center gap-3 p-3 hover:bg-gray-200 rounded-lg cursor-pointer";
        card.textContent = serie.name;
        suggestions.appendChild(card);
      });
  });
};
