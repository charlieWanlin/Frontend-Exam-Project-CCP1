// ─────────────────────────────────────────────
// SECTION : Overlay global
// Gère l'affichage de l'overlay et les callbacks de fermeture
// ─────────────────────────────────────────────

const overlay = document.getElementById("overlay");

// Liste des fonctions à appeler lors de la fermeture de l'overlay
const closeHandlers = [];

// Enregistre une fonction qui sera exécutée à chaque fermeture de l'overlay
export const registerCloseHandler = (fn) => {
  closeHandlers.push(fn);
};

// Affiche l'overlay en retirant la classe "hidden"
export const openOverlay = () => {
  if (overlay) {
    overlay.classList.remove("hidden");
  }
};

// Masque l'overlay et déclenche tous les handlers de fermeture enregistrés
export const closeOverlay = () => {
  if (overlay) {
    overlay.classList.add("hidden");
  }
  closeHandlers.forEach((fn) => fn());
};

// Ferme uniquement si on clique sur le fond
if (overlay) {
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) {
      closeOverlay();
    }
  });
}
