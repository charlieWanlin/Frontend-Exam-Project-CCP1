/**
 * serie-show.js
 * Gère le chargement, filtrage, tri et pagination des looks
 * d'une page série.
 *
 * Dépend de :  window.SERIE_ID  (injecté par la vue PHP)
 * API attendue : GET /api/series/{id}/looks
 *   Paramètres query string : personnage, saison, sort, page, per_page
 *   Réponse JSON :
 *   {
 *     looks: [{ id, titre, photo, personnage, saison, popularite, created_at }],
 *     total: Number
 *   }
 */

(() => {
  'use strict';

  /* ─────────────────────────────────────────
     Config
  ───────────────────────────────────────── */
  const SERIE_ID = window.SERIE_ID;
  const PER_PAGE = 8;

  /* ─────────────────────────────────────────
     État
  ───────────────────────────────────────── */
  const state = {
    personnage : 'tous',
    saison     : 0,       // 0 = toutes les saisons
    sort       : 'popularite',
    page       : 1,
    total      : 0,
    looks      : [],
  };

  /* ─────────────────────────────────────────
     Refs DOM
  ───────────────────────────────────────── */
  const grid        = document.getElementById('looks-grid');
  const emptyMsg    = document.getElementById('looks-empty');
  const countEl     = document.getElementById('looks-count');
  const persoBtns   = document.querySelectorAll('[data-look-perso]');
  const saisonSel   = document.getElementById('saison-select');
  const sortBtn     = document.getElementById('looks-sort-btn');
  const sortLabel   = document.getElementById('looks-sort-label');
  const sortMenu    = document.getElementById('looks-sort-menu');
  const sortOpts    = document.querySelectorAll('[data-look-sort]');
  const pagePrev    = document.getElementById('looks-page-prev');
  const pageNext    = document.getElementById('looks-page-next');
  const pageNumbers = document.getElementById('looks-page-numbers');

  /* ─────────────────────────────────────────
     Fetch looks
  ───────────────────────────────────────── */
  async function fetchLooks() {
    showSkeletons();

    const params = new URLSearchParams({
      personnage : state.personnage,
      saison     : state.saison,
      sort       : state.sort,
      page       : state.page,
      per_page   : PER_PAGE,
    });

    try {
      const res  = await fetch(`/api/series/${SERIE_ID}/looks?${params}`);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();

      state.looks = data.looks ?? [];
      state.total = data.total ?? 0;

      render();
    } catch (err) {
      console.error('[serie-show] Erreur fetch looks :', err);
      renderError();
    }
  }

  /* ─────────────────────────────────────────
     Rendu grille
  ───────────────────────────────────────── */
  function render() {
    countEl.textContent = state.total;

    if (state.looks.length === 0) {
      grid.innerHTML = '';
      emptyMsg.classList.remove('hidden');
      renderPagination();
      return;
    }

    emptyMsg.classList.add('hidden');
    grid.innerHTML = state.looks.map(look => buildLookCard(look)).join('');
    renderPagination();
  }

  /**
   * Construit le HTML d'une carte look.
   * @param {{ id: number, titre: string, photo: string, personnage: string, saison: number }} look
   * @returns {string}
   */
  function buildLookCard(look) {
    const href  = `/looks/${look.id}`;
    const alt   = `Look : ${escapeHtml(look.titre)}`;
    // Affiche "Personnage · Saison N" dans l'overlay
    const parts = [];
    if (look.personnage) parts.push(escapeHtml(look.personnage));
    if (look.saison)     parts.push(`Saison ${look.saison}`);
    const label = parts.join(' · ');

    if (look.photo) {
      return `
        <article class="look-card" role="listitem">
          <a href="${href}" aria-label="${alt}" style="display:block; aspect-ratio:3/4">
            <img
              src="/assets/img/${escapeHtml(look.photo)}"
              alt="${alt}"
              loading="lazy"
              decoding="async"
            >
            <div class="look-card-overlay" aria-hidden="true">
              <p>${label}</p>
            </div>
          </a>
        </article>`;
    }

    // Fallback sans image
    return `
      <article class="look-card" role="listitem" style="aspect-ratio:3/4; display:flex; align-items:center; justify-content:center">
        <a href="${href}" aria-label="${alt}" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center">
          <span style="font-family:var(--font-sans);font-size:.6rem;letter-spacing:.14em;text-transform:uppercase;color:var(--color-muted)">
            ${escapeHtml(look.titre)}
          </span>
        </a>
      </article>`;
  }

  function renderError() {
    grid.innerHTML = `
      <p
        class="col-span-full font-body text-center py-16"
        style="font-size:.875rem; color:var(--color-muted)"
        role="alert"
      >
        Impossible de charger les looks. Veuillez réessayer.
      </p>`;
    countEl.textContent = '—';
  }

  /* ─────────────────────────────────────────
     Skeletons (pendant le chargement)
  ───────────────────────────────────────── */
  function showSkeletons() {
    emptyMsg.classList.add('hidden');
    grid.innerHTML = Array.from({ length: PER_PAGE })
      .map(() => `<div class="skeleton" role="presentation" aria-hidden="true"></div>`)
      .join('');
  }

  /* ─────────────────────────────────────────
     Pagination
  ───────────────────────────────────────── */
  function renderPagination() {
    const totalPages = Math.ceil(state.total / PER_PAGE);

    pagePrev.disabled = state.page <= 1;
    pageNext.disabled = state.page >= totalPages;

    pageNumbers.innerHTML = '';

    if (totalPages <= 1) {
      document.getElementById('looks-pagination').style.display = 'none';
      return;
    }
    document.getElementById('looks-pagination').style.display = '';

    const pages = getPageRange(state.page, totalPages);
    pages.forEach(p => {
      const el = document.createElement(p === '…' ? 'span' : 'button');
      if (p === '…') {
        el.textContent = '…';
        el.style.cssText = 'font-size:.68rem;color:var(--color-muted);padding:0 .25rem;line-height:2';
      } else {
        el.type = 'button';
        el.classList.add('page-btn');
        if (p === state.page) el.classList.add('active');
        el.setAttribute('aria-label', `Page ${p}`);
        el.setAttribute('aria-current', p === state.page ? 'page' : 'false');
        el.textContent = p;
        el.addEventListener('click', () => goToPage(p));
      }
      pageNumbers.appendChild(el);
    });
  }

  function getPageRange(current, total) {
    if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);

    const pages = [];
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) pages.push(i);
      pages.push('…', total);
    } else if (current >= total - 3) {
      pages.push(1, '…');
      for (let i = total - 4; i <= total; i++) pages.push(i);
    } else {
      pages.push(1, '…', current - 1, current, current + 1, '…', total);
    }
    return pages;
  }

  function goToPage(page) {
    state.page = page;
    fetchLooks();
    document.getElementById('section-looks').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  /* ─────────────────────────────────────────
     Événements — filtre personnage
  ───────────────────────────────────────── */
  persoBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const value = btn.dataset.lookPerso;
      if (value === state.personnage) return;

      state.personnage = value;
      state.page       = 1;

      persoBtns.forEach(b => {
        b.classList.toggle('active', b === btn);
        b.setAttribute('aria-pressed', String(b === btn));
      });

      fetchLooks();
    });
  });

  /* ─────────────────────────────────────────
     Événements — filtre saison
  ───────────────────────────────────────── */
  if (saisonSel) {
    saisonSel.addEventListener('change', () => {
      state.saison = parseInt(saisonSel.value, 10) || 0;
      state.page   = 1;
      fetchLooks();
    });
  }

  /* ─────────────────────────────────────────
     Événements — tri
  ───────────────────────────────────────── */
  const SORT_LABELS = {
    'popularite' : 'Populaires',
    'recents'    : 'Récents',
    'alpha-asc'  : 'A → Z',
  };

  sortBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = !sortMenu.classList.contains('hidden');
    sortMenu.classList.toggle('hidden', isOpen);
    sortBtn.setAttribute('aria-expanded', String(!isOpen));
  });

  document.addEventListener('click', () => {
    sortMenu.classList.add('hidden');
    sortBtn.setAttribute('aria-expanded', 'false');
  });

  sortOpts.forEach(opt => {
    opt.addEventListener('click', () => {
      const value = opt.dataset.lookSort;
      if (value === state.sort) return;

      state.sort = value;
      state.page = 1;

      sortLabel.textContent = SORT_LABELS[value] ?? value;

      sortOpts.forEach(o => {
        const selected = o === opt;
        o.setAttribute('aria-selected', String(selected));
        o.style.color      = selected ? 'var(--color-text)' : 'var(--color-muted)';
        o.style.fontWeight = selected ? '500' : '';
      });

      sortMenu.classList.add('hidden');
      sortBtn.setAttribute('aria-expanded', 'false');

      fetchLooks();
    });
  });

  /* ─────────────────────────────────────────
     Événements — pagination
  ───────────────────────────────────────── */
  pagePrev.addEventListener('click', () => goToPage(state.page - 1));
  pageNext.addEventListener('click', () => goToPage(state.page + 1));

  /* ─────────────────────────────────────────
     Utilitaire XSS
  ───────────────────────────────────────── */
  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  /* ─────────────────────────────────────────
     Init
  ───────────────────────────────────────── */
  fetchLooks();

})();