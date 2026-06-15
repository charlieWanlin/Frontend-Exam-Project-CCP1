/**
 * celebrities.js
 * ──────────────────────────────────────────────
 * Gestion du catalogue des célébrités :
 *   - Récupération via l'API  GET /api/celebrities
 *   - Filtres par catégorie
 *   - Tri
 *   - Pagination
 * ──────────────────────────────────────────────
 */

(function () {

  /* ═══════════════════════════════════════════
     1. ÉTAT
  ═══════════════════════════════════════════ */
  let activeCategorie = 'tous';
  let activeSort      = 'popularite';
  let page            = 1;
  let totalPages      = 1;
  const PAGE_SIZE     = 16;
  let isLoading       = false;


  /* ═══════════════════════════════════════════
     2. RÉFÉRENCES AU DOM
  ═══════════════════════════════════════════ */
  const DOM = {
    grid:         document.getElementById('celebrities-grid'),
    empty:        document.getElementById('celeb-empty'),
    countEl:      document.getElementById('celebs-count'),
    sortBtn:      document.getElementById('sort-btn'),
    sortMenu:     document.getElementById('sort-menu'),
    sortLabel:    document.getElementById('sort-label'),
    filterToggle: document.getElementById('filter-toggle'),
    filterMenu:   document.getElementById('filter-menu'),
    pagination:   document.getElementById('pagination'),
    pageNumbers:  document.getElementById('page-numbers'),
    pagePrev:     document.getElementById('page-prev'),
    pageNext:     document.getElementById('page-next'),
    catalogue:    document.getElementById('section-catalogue'),
  };


  /* ═══════════════════════════════════════════
     3. UTILITAIRES
  ═══════════════════════════════════════════ */

  function escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function scrollToCatalogue() {
    DOM.catalogue.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }


  /* ═══════════════════════════════════════════
     4. RENDU DES CARTES
  ═══════════════════════════════════════════ */
  function renderCard(c) {
    return `
      <article class="celeb-card cursor-pointer" role="listitem">
        <a href="/celebrities/${escHtml(c.slug)}">
          <div class="overflow-hidden" style="aspect-ratio:3/4;background:#f0ede8">
            <img
              src="/assets/img/${escHtml(c.photo)}"
              alt="${escHtml(c.nom)}"
              class="celeb-img w-full h-full object-cover object-top"
              loading="lazy"
            />
          </div>
          <div class="pt-4 px-1">
            <h3 class="text-sm tracking-wide text-[#1a1a1a] font-body">${escHtml(c.nom)}</h3>
            ${c.categorie
              ? `<p class="text-xs text-muted font-light mt-1 font-body">${escHtml(c.categorie)}</p>`
              : ''}
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-border">
              <span class="looks-count">${parseInt(c.nb_looks, 10)} looks</span>
              <span class="celeb-arrow">Voir →</span>
            </div>
          </div>
        </a>
      </article>`;
  }


  /* ═══════════════════════════════════════════
     5. SKELETONS
  ═══════════════════════════════════════════ */
  function showSkeletons() {
    DOM.grid.innerHTML = Array(8)
      .fill('<div class="skeleton rounded-none" role="listitem"></div>')
      .join('');
  }


  /* ═══════════════════════════════════════════
     6. PAGINATION
  ═══════════════════════════════════════════ */
  function renderPagination(total) {
    totalPages = Math.ceil(total / PAGE_SIZE);

    DOM.pagination.classList.toggle('hidden', totalPages <= 1);

    DOM.pagePrev.disabled = (page <= 1);
    DOM.pageNext.disabled = (page >= totalPages);

    let start = Math.max(1, page - 2);
    let end   = Math.min(totalPages, start + 4);
    if (end - start < 4) start = Math.max(1, end - 4);

    DOM.pageNumbers.innerHTML = '';

    if (start > 1) {
      DOM.pageNumbers.insertAdjacentHTML('beforeend', makePageBtn(1));
      if (start > 2) {
        DOM.pageNumbers.insertAdjacentHTML('beforeend',
          `<span class="text-muted text-xs px-1 self-center">…</span>`);
      }
    }

    for (let i = start; i <= end; i++) {
      DOM.pageNumbers.insertAdjacentHTML('beforeend', makePageBtn(i));
    }

    if (end < totalPages) {
      if (end < totalPages - 1) {
        DOM.pageNumbers.insertAdjacentHTML('beforeend',
          `<span class="text-muted text-xs px-1 self-center">…</span>`);
      }
      DOM.pageNumbers.insertAdjacentHTML('beforeend', makePageBtn(totalPages));
    }

    DOM.pageNumbers.querySelectorAll('.page-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        page = parseInt(btn.dataset.p, 10);
        fetchCelebrities();
        scrollToCatalogue();
      });
    });
  }

  function makePageBtn(n) {
    const isActive = (n === page) ? ' active' : '';
    return `<button class="page-btn${isActive}" data-p="${n}">${n}</button>`;
  }


  /* ═══════════════════════════════════════════
     7. FETCH PRINCIPAL
  ═══════════════════════════════════════════ */
  async function fetchCelebrities() {
    if (isLoading) return;
    isLoading = true;

    showSkeletons();
    DOM.empty.classList.add('hidden');
    DOM.pagination.classList.add('hidden');

    const params = new URLSearchParams();
    if (activeCategorie !== 'tous') params.set('categorie', activeCategorie);
    params.set('sort',     activeSort);
    params.set('page',     String(page));
    params.set('per_page', String(PAGE_SIZE));

    try {
      const res  = await fetch(`/api/celebrities?${params}`);
      if (!res.ok) throw new Error(`Erreur HTTP ${res.status}`);

      const data  = await res.json();
      const list  = Array.isArray(data?.items) ? data.items : [];
      const total = typeof data?.total === 'number' ? data.total : 0;

      DOM.grid.innerHTML = '';

      if (!list.length) {
        DOM.empty.classList.remove('hidden');
        DOM.countEl.textContent = '0';
        return;
      }

      DOM.grid.insertAdjacentHTML('beforeend', list.map(renderCard).join(''));
      DOM.countEl.textContent = String(total);
      renderPagination(total);

    } catch (err) {
      DOM.grid.innerHTML = `
        <p class="text-muted text-xs col-span-full text-center py-10 font-body">
          Une erreur est survenue. Veuillez réessayer.
        </p>`;
      console.error('[celebrities.js]', err);
    } finally {
      isLoading = false;
    }
  }


  /* ═══════════════════════════════════════════
     8. ÉVÉNEMENTS — FILTRES PAR CATÉGORIE
     CORRECTION : le sélecteur était '[data-filter]'
     dans le HTML (class="filter-pill") mais le JS
     cherchait '.filter-btn' → aucun élément trouvé.
     On cible désormais [data-filter] directement.
  ═══════════════════════════════════════════ */
  document.querySelectorAll('[data-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
      // Désactive tous les boutons de filtre (desktop + mobile)
      document.querySelectorAll('[data-filter]').forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-pressed', 'false');
      });
      // Active celui cliqué
      btn.classList.add('active');
      btn.setAttribute('aria-pressed', 'true');

      activeCategorie = btn.dataset.filter || 'tous';
      page = 1;
      fetchCelebrities();
    });
  });


  /* ═══════════════════════════════════════════
     9. ÉVÉNEMENTS — TOGGLE FILTRES MOBILE
  ═══════════════════════════════════════════ */
  DOM.filterToggle?.addEventListener('click', e => {
    e.stopPropagation();
    const isOpen = !DOM.filterMenu.classList.contains('hidden');
    DOM.filterMenu.classList.toggle('hidden');
    DOM.filterToggle.setAttribute('aria-expanded', String(!isOpen));
  });


  /* ═══════════════════════════════════════════
     10. ÉVÉNEMENTS — TRI
  ═══════════════════════════════════════════ */
  DOM.sortBtn?.addEventListener('click', e => {
    e.stopPropagation();
    const isOpen = !DOM.sortMenu.classList.contains('hidden');
    DOM.sortMenu.classList.toggle('hidden');
    DOM.sortBtn.setAttribute('aria-expanded', String(!isOpen));
  });

  DOM.sortMenu?.querySelectorAll('.sort-opt').forEach(opt => {
    opt.addEventListener('click', e => {
      e.stopPropagation();

      DOM.sortMenu.querySelectorAll('.sort-opt').forEach(o => {
        o.classList.remove('text-[#1a1a1a]', 'font-medium');
      });
      opt.classList.add('text-[#1a1a1a]', 'font-medium');

      activeSort = opt.dataset.sort;
      DOM.sortLabel.textContent = opt.textContent.trim();
      DOM.sortMenu.classList.add('hidden');
      DOM.sortBtn.setAttribute('aria-expanded', 'false');

      page = 1;
      fetchCelebrities();
    });
  });


  /* ═══════════════════════════════════════════
     11. ÉVÉNEMENTS — PAGINATION PREV / NEXT
  ═══════════════════════════════════════════ */
  DOM.pagePrev?.addEventListener('click', () => {
    if (page > 1) {
      page--;
      fetchCelebrities();
      scrollToCatalogue();
    }
  });

  DOM.pageNext?.addEventListener('click', () => {
    if (page < totalPages) {
      page++;
      fetchCelebrities();
      scrollToCatalogue();
    }
  });


  /* ═══════════════════════════════════════════
     12. FERMETURE DES MENUS AU CLIC EXTÉRIEUR
  ═══════════════════════════════════════════ */
  document.addEventListener('click', () => {
    DOM.sortMenu?.classList.add('hidden');
    DOM.sortBtn?.setAttribute('aria-expanded', 'false');
    DOM.filterMenu?.classList.add('hidden');
    DOM.filterToggle?.setAttribute('aria-expanded', 'false');
  });


  /* ═══════════════════════════════════════════
     13. INITIALISATION
  ═══════════════════════════════════════════ */
  fetchCelebrities();

})();