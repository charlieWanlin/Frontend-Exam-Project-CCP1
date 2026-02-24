import { openOverlay, closeOverlay, registerCloseHandler } from "./overlay.js";

export const initCompte = () => {
  // --- Sélection des éléments du DOM ---
  const compteBtn = document.querySelector("#compte-btn");
  const modalCompte = document.querySelector("#modal-compte");
  const modalCompteClose = document.querySelector("#modal-compte-close");
  const tabConnexion = document.querySelector("#tab-connexion");
  const tabInscription = document.querySelector("#tab-inscription");
  const formConnexion = document.querySelector("#form-connexion");
  const formInscription = document.querySelector("#form-inscription");

  // Si les éléments essentiels sont absents, on stoppe l'initialisation
  if (!compteBtn || !modalCompte) return;

  // --- Ouverture / Fermeture de la modale ---
  const close = () => modalCompte.classList.add("hidden");
  const open = () => {
    modalCompte.classList.remove("hidden");
    openOverlay(); // Active l'overlay sombre en arrière-plan
  };

  registerCloseHandler(close); // Enregistre la fermeture auprès du gestionnaire global d'overlay
  compteBtn.addEventListener("click", open);
  modalCompteClose?.addEventListener("click", closeOverlay);

  // Ferme la modale en cliquant sur le fond (wrapper plein écran)
  modalCompte.addEventListener("click", (e) => {
    if (e.target === modalCompte) closeOverlay();
  });

  // --- Gestion des onglets Connexion / Inscription ---
  // Active un onglet et son formulaire associé, désactive l'autre
  const activateTab = (activeTab, inactiveTab, activeForm, inactiveForm) => {
    // Style de l'onglet actif
    activeTab.classList.add("text-gray-900", "border-b-2", "border-gray-900");
    activeTab.classList.remove("text-gray-400");

    // Style de l'onglet inactif
    inactiveTab.classList.remove(
      "text-gray-900",
      "border-b-2",
      "border-gray-900",
    );
    inactiveTab.classList.add("text-gray-400");

    // Affiche le bon formulaire, masque l'autre
    activeForm.classList.remove("hidden");
    inactiveForm.classList.add("hidden");
  };

  // Clics sur les onglets principaux
  tabInscription?.addEventListener("click", () =>
    activateTab(tabInscription, tabConnexion, formInscription, formConnexion),
  );
  tabConnexion?.addEventListener("click", () =>
    activateTab(tabConnexion, tabInscription, formConnexion, formInscription),
  );

  // Liens de switch depuis l'intérieur des formulaires (ex: "Pas encore de compte ?")
  document
    .querySelector("#switch-to-inscription")
    ?.addEventListener("click", () =>
      activateTab(tabInscription, tabConnexion, formInscription, formConnexion),
    );
  document
    .querySelector("#switch-to-connexion")
    ?.addEventListener("click", () =>
      activateTab(tabConnexion, tabInscription, formConnexion, formInscription),
    );
};
