(() => {
  const grid         = document.getElementById('series-grid');
  const countEl      = document.getElementById('series-count');
  const emptyEl      = document.getElementById('serie-empty');
  const prevBtn      = document.getElementById('page-prev');
  const nextBtn      = document.getElementById('page-next');
  const pageNums     = document.getElementById('page-numbers');
  const sortBtn      = document.getElementById('sort-btn');
  const sortMenu     = document.getElementById('sort-menu');
  const sortLabel    = document.getElementById('sort-label');
  const filterToggle = document.getElementById('filter-toggle');
  const filterMenu   = document.getElementById('filter-menu');

  let state = { categorie: 'tous', sort: 'popularite', page: 1, total: 0 };
  const PER_PAGE = 16;

  // ── Fetch ──────────────────────────────────────────
  async function load() {
    const params = new URLSearchParams({
      categorie: state.categorie,
      sort:      state.sort,
      page:      state.page,
      per_page:  PER_PAGE,
    });

    grid.innerHTML = Array(8).fill('<div class="skeleton rounded-none" role="listitem"></div>').join('');
    emptyEl.classList.add('hidden');

    try {
      const res  = await fetch(`/api/series?${params}`);
      const data = await res.json();

      if (!res.ok) {
        console.error('Erreur API:', data.error);
        grid.innerHTML = '<p class="col-span-4 text-center text-sm text-muted py-16">Erreur de chargement.</p>';
        return;
      }

      state.total = data.total;
      countEl.textContent = data.total;
      render(data.items);
      renderPagination();

    } catch (err) {
      console.error('Erreur réseau:', err);
      grid.innerHTML = '<p class="col-span-4 text-center text-sm text-muted py-16">Erreur de chargement.</p>';
    }
  }

  // ── Rendu des cartes ───────────────────────────────
  function render(items) {
    if (!items || !items.length) {
      grid.innerHTML = '';
      emptyEl.classList.remove('hidden');
      return;
    }

    grid.innerHTML = items.map(s => `
      <article class="serie-card group cursor-pointer" role="listitem">
        <a href="/series/${encodeURIComponent(s.slug)}" class="block">
          <div class="overflow-hidden" style="aspect-ratio:2/3">
            <img
              src="/assets/img/${s.photo}"
              alt="${s.nom}"
              class="serie-img w-full h-full object-cover object-top"
              loading="lazy"
            />
          </div>
          <div class="mt-2.5 flex items-start justify-between gap-2">
            <div>
              <p class="font-heading text-sm font-normal leading-tight">${s.nom}</p>
              <p class="font-body text-[.6rem] text-muted mt-0.5 tracking-widest uppercase">${s.style ?? ''}</p>
            </div>
            <span class="looks-count shrink-0">${s.nb_looks ?? 0}</span>
          </div>
          <p class="serie-arrow mt-1">Voir les looks →</p>
        </a>
      </article>
    `).join('');
  }

  // ── Pagination ─────────────────────────────────────
  function renderPagination() {
    const totalPages = Math.ceil(state.total / PER_PAGE);
    prevBtn.disabled = state.page <= 1;
    nextBtn.disabled = state.page >= totalPages;

    pageNums.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement('button');
      btn.className = 'page-btn' + (i === state.page ? ' active' : '');
      btn.textContent = i;
      btn.addEventListener('click', () => { state.page = i; load(); });
      pageNums.appendChild(btn);
    }
  }

  // ── Filtres ────────────────────────────────────────
  document.querySelectorAll('[data-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('[data-filter]').forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-pressed', 'false');
      });
      document.querySelectorAll(`[data-filter="${btn.dataset.filter}"]`).forEach(b => {
        b.classList.add('active');
        b.setAttribute('aria-pressed', 'true');
      });
      state.categorie = btn.dataset.filter;
      state.page = 1;
      load();
    });
  });

  // ── Tri ────────────────────────────────────────────
  sortBtn?.addEventListener('click', () => {
    const open = sortMenu.classList.toggle('hidden');
    sortBtn.setAttribute('aria-expanded', String(!open));
  });

  document.querySelectorAll('.sort-opt').forEach(opt => {
    opt.addEventListener('click', () => {
      document.querySelectorAll('.sort-opt').forEach(o => o.classList.remove('text-[#1a1a1a]', 'font-medium'));
      opt.classList.add('text-[#1a1a1a]', 'font-medium');
      sortLabel.textContent = opt.textContent;
      sortMenu.classList.add('hidden');
      state.sort = opt.dataset.sort;
      state.page = 1;
      load();
    });
  });

  document.addEventListener('click', e => {
    if (!sortBtn?.contains(e.target) && !sortMenu?.contains(e.target)) {
      sortMenu?.classList.add('hidden');
    }
  });

  // ── Toggle mobile ──────────────────────────────────
  filterToggle?.addEventListener('click', () => {
    const hidden = filterMenu.classList.toggle('hidden');
    filterToggle.setAttribute('aria-expanded', String(!hidden));
  });

  // ── Prev / Next ────────────────────────────────────
  prevBtn?.addEventListener('click', () => { state.page--; load(); });
  nextBtn?.addEventListener('click', () => { state.page++; load(); });

  // ── Init ───────────────────────────────────────────
  load();
})();