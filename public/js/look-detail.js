// ─────────────────────────────────────────────
// look-detail.html — Image source from ?id= or ?source=upload
// ─────────────────────────────────────────────

const SCAN_KEY = "ledressing_scan_image";

document.addEventListener("DOMContentLoaded", () => {
  const img = document.getElementById("look-image");
  const titleEl = document.getElementById("look-title");
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get("id");
  const source = urlParams.get("source");

  if (source === "upload") {
    const dataUrl = sessionStorage.getItem(SCAN_KEY);
    if (dataUrl && dataUrl.startsWith("data:")) {
      img.src = dataUrl;
      if (titleEl) titleEl.textContent = "Votre look analysé";
    }
    return;
  }

  if (id) {
    import("./data/outfits.js").then(({ outfits }) => {
      const outfit = outfits.find((o) => String(o.id) === id);
      if (outfit) {
        let src = outfit.img;
        if (src.startsWith("../../")) src = "./" + src.slice(6);
        img.src = src;
        img.alt = outfit.title;
        if (titleEl) titleEl.textContent = outfit.title;
        const brandEl = document.getElementById("original-brand");
        const priceEl = document.getElementById("original-price");
        if (brandEl) brandEl.textContent = "Saint Laurent";
        if (priceEl) priceEl.textContent = "1 290 €";
      }
    });
  }
});
