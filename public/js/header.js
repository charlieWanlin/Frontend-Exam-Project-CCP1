/**
 * header.js
 * Gère : navbar scroll, search sidebar, mobile drawer.
 * Les modals auth ont été remplacés par des pages dédiées (/login, /register).
 */

(() => {
  'use strict';

  /* ─────────────────────────────────────────
     Refs
  ───────────────────────────────────────── */
  const navbar        = document.getElementById('navbar');
  const overlay       = document.getElementById('overlay');
  const searchBtn     = document.getElementById('search-btn');
  const searchClose   = document.getElementById('search-close');
  const searchSidebar = document.getElementById('search-sidebar');
  const searchInput   = document.getElementById('search-input');
  const mobileBtn     = document.getElementById('mobile-menu-btn');
  const mobileMenu    = document.getElementById('mobile-menu');

  /* ─────────────────────────────────────────
     Navbar — masquer au scroll bas
  ───────────────────────────────────────── */
  let lastScroll = 0;
  window.addEventListener('scroll', () => {
    const current = window.scrollY;
    if (current <= 10) {
      navbar.style.transform = 'translateY(0)';
    } else if (current > lastScroll) {
      navbar.style.transform = 'translateY(-100%)';
    } else {
      navbar.style.transform = 'translateY(0)';
    }
    lastScroll = current;
  }, { passive: true });

  /* ─────────────────────────────────────────
     Helpers overlay
  ───────────────────────────────────────── */
  function showOverlay() {
    overlay.classList.remove('hidden');
    requestAnimationFrame(() => overlay.classList.add('opacity-100'));
  }
  function hideOverlay() {
    overlay.classList.remove('opacity-100');
    setTimeout(() => overlay.classList.add('hidden'), 300);
  }

  /* ─────────────────────────────────────────
     Search sidebar
  ───────────────────────────────────────── */
  function openSearch() {
    searchSidebar.classList.remove('translate-x-full');
    showOverlay();
    document.body.style.overflow = 'hidden';
    setTimeout(() => searchInput?.focus(), 300);
  }

  function closeSearch() {
    searchSidebar.classList.add('translate-x-full');
    hideOverlay();
    document.body.style.overflow = '';
    if (searchInput) {
      searchInput.value = '';
      resetSuggestions();
    }
  }

  searchBtn?.addEventListener('click', openSearch);
  searchClose?.addEventListener('click', closeSearch);

  /* ─────────────────────────────────────────
     Mobile drawer
  ───────────────────────────────────────── */
  function openMobile() {
    mobileMenu.classList.remove('hidden');
    mobileMenu.classList.add('flex');
    showOverlay();
    document.body.style.overflow = 'hidden';
    mobileBtn.setAttribute('aria-expanded', 'true');

    const bars = mobileBtn.querySelectorAll('span');
    bars[0].style.transform = 'translateY(6px) rotate(45deg)';
    bars[1].style.opacity   = '0';
    bars[2].style.transform = 'translateY(-6px) rotate(-45deg)';
  }

  function closeMobile() {
    mobileMenu.classList.add('hidden');
    mobileMenu.classList.remove('flex');
    hideOverlay();
    document.body.style.overflow = '';
    mobileBtn.setAttribute('aria-expanded', 'false');

    const bars = mobileBtn.querySelectorAll('span');
    bars[0].style.transform = '';
    bars[1].style.opacity   = '';
    bars[2].style.transform = '';
  }

  mobileBtn?.addEventListener('click', () => {
    const isOpen = mobileBtn.getAttribute('aria-expanded') === 'true';
    isOpen ? closeMobile() : openMobile();
  });

  /* ─────────────────────────────────────────
     Overlay clique → tout fermer
  ───────────────────────────────────────── */
  overlay?.addEventListener('click', () => {
    closeSearch();
    closeMobile();
  });

  /* ─────────────────────────────────────────
     Échap → tout fermer
  ───────────────────────────────────────── */
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    closeSearch();
    closeMobile();
  });

  /* ─────────────────────────────────────────
     Recherche live
  ───────────────────────────────────────── */
  const suggestionsEl = document.getElementById('search-suggestions');
  let searchTimer;
  let currentQuery = '';
  let selectedIndex = -1;   // navigation clavier dans les résultats

  /* État vide — suggestions par défaut */
  function resetSuggestions() {
    selectedIndex = -1;
    currentQuery  = '';
    suggestionsEl.innerHTML = `
      <p class="text-[10px] tracking-widest text-gray-300 uppercase px-2 mb-3">Suggestions</p>
      <div class="flex flex-wrap gap-2 px-2">
        <a href="/celebrities"  class="text-xs text-gray-500 border border-gray-200 px-3 py-1.5 hover:border-black hover:text-black transition-colors duration-200">Célébrités</a>
        <a href="/series"       class="text-xs text-gray-500 border border-gray-200 px-3 py-1.5 hover:border-black hover:text-black transition-colors duration-200">Séries</a>
        <a href="/style-finder" class="text-xs text-gray-500 border border-gray-200 px-3 py-1.5 hover:border-black hover:text-black transition-colors duration-200">Style Finder</a>
      </div>`;
  }

  /* Skeleton pendant le fetch */
  function showSkeleton() {
    suggestionsEl.innerHTML = `
      <div class="flex flex-col gap-3 px-2 pt-2">
        ${Array.from({ length: 3 }).map(() => `
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gray-100 shrink-0 animate-pulse"></div>
            <div class="flex-1 h-3 bg-gray-100 rounded animate-pulse"></div>
          </div>`).join('')}
      </div>`;
  }

  /* Écoute de la frappe */
  searchInput?.addEventListener('input', () => {
    clearTimeout(searchTimer);
    selectedIndex = -1;

    const q = searchInput.value.trim();
    currentQuery = q;

    if (q.length < 2) {
      resetSuggestions();
      return;
    }

    showSkeleton();
    searchTimer = setTimeout(() => fetchSearch(q), 350);
  });

  /* Navigation clavier ↑ ↓ Entrée dans les résultats */
  searchInput?.addEventListener('keydown', e => {
    const links = [...suggestionsEl.querySelectorAll('a[href]')];
    if (!links.length) return;

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      selectedIndex = Math.min(selectedIndex + 1, links.length - 1);
      highlightLink(links);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      selectedIndex = Math.max(selectedIndex - 1, -1);
      highlightLink(links);
    } else if (e.key === 'Enter' && selectedIndex >= 0) {
      e.preventDefault();
      links[selectedIndex].click();
    }
  });

  function highlightLink(links) {
    links.forEach((l, i) => {
      l.classList.toggle('bg-gray-50', i === selectedIndex);
    });
    if (selectedIndex >= 0) links[selectedIndex].focus();
    else searchInput.focus();
  }

  /* Fetch vers l'API PHP */
  async function fetchSearch(q) {
    // Vérifie que la query n'a pas changé pendant le debounce
    if (q !== currentQuery) return;

    try {
      const res = await fetch(`/api/search?q=${encodeURIComponent(q)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      if (!res.ok) throw new Error(`HTTP ${res.status}`);

      const data = await res.json();

      // Sécurité : si une autre requête plus récente est en cours, on ignore
      if (q !== currentQuery) return;

      renderSearchResults(data.results ?? [], q);

    } catch (err) {
      console.warn('[search]', err);
      suggestionsEl.innerHTML = `
        <p class="text-xs text-gray-400 px-2 py-4">
          Une erreur est survenue. Réessaie dans un instant.
        </p>`;
    }
  }

  /* Rendu des résultats */
  function renderSearchResults(results, q) {
    if (!results.length) {
      suggestionsEl.innerHTML = `
        <p class="text-xs text-gray-400 px-2 py-4">
          Aucun résultat pour « <strong>${escapeHtml(q)}</strong> ».
        </p>`;
      return;
    }

    suggestionsEl.innerHTML = `
      <p class="text-[10px] tracking-widest text-gray-300 uppercase px-2 mb-3">
        ${results.length} résultat${results.length > 1 ? 's' : ''}
      </p>
      <ul class="flex flex-col" role="listbox">
        ${results.map((r, i) => `
          <li role="option">
            <a href="${escapeHtml(r.url)}"
               class="flex items-center gap-3 px-2 py-2.5 hover:bg-gray-50 transition-colors duration-150 group"
               tabindex="-1"
               data-index="${i}">
              ${r.photo
                ? `<img src="/assets/img/${escapeHtml(r.photo)}"
                        alt="${escapeHtml(r.nom)}"
                        class="w-10 h-10 object-cover object-top shrink-0 bg-gray-100"
                        loading="lazy" />`
                : `<div class="w-10 h-10 bg-gray-100 shrink-0 flex items-center justify-center text-gray-300 text-xs">?</div>`
              }
              <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-800 truncate group-hover:text-black">
                  ${highlightQuery(escapeHtml(r.nom), escapeHtml(q))}
                </p>
                <p class="text-[10px] tracking-widest text-gray-400 uppercase mt-0.5">
                  ${escapeHtml(r.type ?? '')}
                </p>
              </div>
            </a>
          </li>`).join('')}
      </ul>`;
  }

  /* Met en gras la partie de la chaîne qui correspond à la query */
  function highlightQuery(nom, q) {
    const regex = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return nom.replace(regex, '<mark class="bg-transparent font-semibold text-black">$1</mark>');
  }

  /* Échapper l'HTML pour éviter les XSS */
  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /* ─────────────────────────────────────────
     Lien actif dans la navbar
  ───────────────────────────────────────── */
  const currentPath = window.location.pathname;
  document.querySelectorAll('#navbar a[href]').forEach(link => {
    if (link.getAttribute('href') === currentPath) {
      link.classList.remove('text-gray-400');
      link.classList.add('text-black');
    }
  });

  /* Init */
  resetSuggestions();

})();