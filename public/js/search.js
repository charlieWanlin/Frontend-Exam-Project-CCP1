// search.js — sidebar recherche : séries + célébrités avec badge type

import { series } from "./data/series.js";
import { celebrities } from "./data/celebs.js";
import { openOverlay, closeOverlay, registerCloseHandler } from "./overlay.js";

export const initSearch = () => {
  const trigger = document.getElementById("search-btn");
  const sidebar = document.getElementById("search-sidebar");
  const closeBtn = document.getElementById("search-close");
  const input = document.querySelector("#search-input");
  const suggestions = document.getElementById("search-suggestions");

  if (!trigger || !sidebar) return;

  const close = () => {
    sidebar.classList.add("translate-x-full");
    input.value = "";
    suggestions.innerHTML = "";
  };

  const open = () => {
    sidebar.classList.remove("translate-x-full");
    openOverlay();
  };

  registerCloseHandler(close);
  trigger.addEventListener("click", (e) => {
    e.stopPropagation();
    open();
  });
  closeBtn.addEventListener("click", closeOverlay);

  // Source unifiée : séries + célébrités
  const all = [
    ...series.map((s) => ({
      name: s.name,
      type: "Série",
      href: `catalog-outfits-series.html?serie=${s.slug}`,
    })),
    ...celebrities.map((c) => ({
      name: c.name,
      type: "Célébrité",
      href: `catalog-outfits.html?celeb=${c.slug}`,
    })),
  ];

  input.addEventListener("input", (e) => {
    const query = e.target.value.toLowerCase().trim();
    suggestions.innerHTML = "";

    if (query.length < 2) return;

    const results = all.filter((x) => x.name.toLowerCase().includes(query));

    // Aucun résultat
    if (!results.length) {
      const empty = document.createElement("p");
      empty.className = "text-sm text-gray-400 text-center py-6 px-3";
      empty.textContent = `Aucun résultat pour « ${e.target.value} »`;
      suggestions.appendChild(empty);
      return;
    }

    results.forEach((item) => {
      const el = document.createElement("a");
      el.href = item.href;
      el.className =
        "flex items-center justify-between gap-3 px-3 py-3 hover:bg-gray-50 rounded-lg cursor-pointer no-underline transition-colors duration-150 group";
      el.innerHTML = `
        <span class="text-sm text-gray-900 truncate">${item.name}</span>
        <span class="text-[10px] tracking-widest uppercase flex-shrink-0 px-2 py-0.5 rounded-sm
          ${item.type === "Célébrité" ? "bg-amber-50 text-amber-600" : "bg-gray-100 text-gray-500"}">
          ${item.type}
        </span>
      `;
      suggestions.appendChild(el);
    });
  });

  // Entrée → premier résultat
  input.addEventListener("keydown", (e) => {
    if (e.key !== "Enter") return;
    const q = input.value.toLowerCase().trim();
    const hit = all.find((x) => x.name.toLowerCase().includes(q));
    if (hit) window.location.href = hit.href;
  });
};
