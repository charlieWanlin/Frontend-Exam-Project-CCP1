// ─────────────────────────────────────────────
// SECTION : Style Finder — Upload et analyse photo
// Gère la zone de dépôt, la préview et la redirection vers look-detail
// ─────────────────────────────────────────────

const dropZone = document.getElementById("drop-zone");
const fileInput = document.getElementById("file-input");
const dropContent = document.getElementById("drop-content");
const previewWrapper = document.getElementById("preview-wrapper");
const previewImg = document.getElementById("preview-img");
const analyzeBtnWrapper = document.getElementById("analyze-btn-wrapper");
const chooseBtn = document.getElementById("choose-btn");
const removeBtn = document.getElementById("remove-btn");
const analyzeBtn = document.getElementById("analyze-btn");
const ctaScroll = document.getElementById("cta-scroll");

if (!dropZone || !fileInput) {
  // Ne pas initialiser si on n'est pas sur la page Style Finder
  document.documentElement.classList.add("style-finder-unused");
} else {
// ─── CLIC SUR LA DROP ZONE → ouvre le sélecteur de fichier ─
dropZone.addEventListener("click", () => {
  fileInput.click();
});

// ─── CLIC SUR "CHOISIR UNE PHOTO" ──────────────────────────
// On stopPropagation pour éviter que le clic remonte sur dropZone
// et déclenche deux fois fileInput.click()
chooseBtn.addEventListener("click", (e) => {
  e.stopPropagation();
  fileInput.click();
});

// ─── SÉLECTION VIA L'INPUT FILE ────────────────────────────
// Se déclenche quand l'utilisateur choisit un fichier via le navigateur
fileInput.addEventListener("change", (e) => {
  const file = e.target.files[0]; // on prend le premier fichier sélectionné
  if (file) showPreview(file);
});


// ─── DRAG AND DROP ─────────────────────────────────────────

// 1. dragover — se déclenche en continu pendant qu'on survole la zone avec un fichier
// Sans e.preventDefault() le drop est bloqué par le navigateur par défaut
dropZone.addEventListener("dragover", (e) => {
  e.preventDefault(); // autorise le drop
  dropZone.classList.add("border-gray-600", "bg-[#f0ece6]"); // feedback visuel
});

// 2. dragleave — se déclenche quand on quitte la zone sans lâcher
dropZone.addEventListener("dragleave", () => {
  dropZone.classList.remove("border-gray-600", "bg-[#f0ece6]"); // retire le feedback
});

// 3. drop — se déclenche quand on lâche le fichier sur la zone
dropZone.addEventListener("drop", (e) => {
  e.preventDefault(); // empêche le navigateur d'ouvrir le fichier dans un nouvel onglet

  dropZone.classList.remove("border-gray-600", "bg-[#f0ece6]"); // retire le feedback

  // e.dataTransfer.files contient les fichiers déposés
  const file = e.dataTransfer.files[0]; // on prend le premier

  // on vérifie que c'est bien une image avant de continuer
  if (file && file.type.startsWith("image/")) {
    showPreview(file);
  }
});


// ─── AFFICHAGE DE LA PREVIEW ───────────────────────────────
function showPreview(file) {
  // FileReader lit le fichier localement sans l'uploader nulle part
  const reader = new FileReader();

  // onload se déclenche quand la lecture est terminée
  reader.onload = (e) => {
    previewImg.src = e.target.result; // e.target.result = l'image en base64

    // on cache le contenu par défaut
    dropContent.classList.add("hidden");

    // on affiche la preview
    previewWrapper.classList.remove("hidden");
    previewWrapper.classList.add("flex");

    // on affiche le bouton analyser
    analyzeBtnWrapper.classList.remove("hidden");
  };

  // readAsDataURL convertit le fichier en string base64 lisible par <img>
  reader.readAsDataURL(file);
}


// ─── SUPPRESSION DE L'IMAGE ────────────────────────────────
removeBtn.addEventListener("click", (e) => {
  e.stopPropagation(); // empêche le clic de remonter sur dropZone et rouvrir le file input

  previewImg.src = "";
  previewWrapper.classList.add("hidden");
  previewWrapper.classList.remove("flex");
  dropContent.classList.remove("hidden");
  analyzeBtnWrapper.classList.add("hidden");
  fileInput.value = ""; // reset l'input pour pouvoir re-sélectionner le même fichier
});


// ─── BOUTON ANALYSER ───────────────────────────────────────
const SCAN_KEY = "ledressing_scan_image";
analyzeBtn.addEventListener("click", () => {
  const dataUrl = previewImg.src;
  if (dataUrl && dataUrl.startsWith("data:")) {
    sessionStorage.setItem(SCAN_KEY, dataUrl);
    window.location.href = "look-detail.html?source=upload";
  }
});


// ─── CTA SCROLL VERS LE HAUT ───────────────────────────────
if (ctaScroll) {
  ctaScroll.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
}
}