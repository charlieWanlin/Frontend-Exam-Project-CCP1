<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- ── Hero sombre ── -->
<section class="relative overflow-hidden py-24 px-[5vw]" style="background:var(--color-dark)" aria-label="En-tête looks">
  <!-- Texte watermark en arrière-plan -->
  <span class="absolute right-[3vw] bottom-[-1.2rem] font-bold text-transparent select-none pointer-events-none" style="font-family:var(--font-heading); font-size:clamp(6rem,18vw,16rem); -webkit-text-stroke:1px rgba(255,255,255,.07)">LOOKS</span>

  <div class="max-w-[1280px] mx-auto relative z-10">
    <p class="eyebrow anim-fu-1 mb-6">Catalogue · Mode · Culture</p>
    <h1 class="anim-fu-2" style="font-family:var(--font-heading); font-size:clamp(2.8rem,7vw,6rem); font-weight:600; line-height:.9; letter-spacing:-.025em; color:var(--color-cream); max-width:14ch">
      Tous les<br><em style="font-style:italic; color:var(--color-gold)">looks.</em>
    </h1>
    <div class="anim-fu-3 flex items-center gap-8 mt-8 flex-wrap">
      <?php foreach (['+12 000'=>'Looks référencés','54'=>'Célébrités','30'=>'Séries'] as $val=>$lbl): ?>
        <div class="flex flex-col gap-0.5">
          <span style="font-family:var(--font-heading); font-size:1.6rem; font-weight:600; color:var(--color-cream); line-height:1"><?= $val ?></span>
          <span class="text-[.54rem] tracking-[.2em] uppercase text-white/30"><?= $lbl ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── Barre de contrôles sticky ── -->
<?php $cats = ['tous'=>'Tous','tapis rouge'=>'Tapis rouge','événement'=>'Événement','quotidien'=>'Quotidien','scène'=>'Scène','sport'=>'Sport','vintage'=>'Vintage','costume'=>'Costume']; ?>

<div class="sticky top-0 z-30 bg-[var(--color-cream)] border-b border-[var(--color-border)]" role="toolbar" aria-label="Filtres et tri">
  <div class="max-w-[1280px] mx-auto px-[5vw] flex items-center justify-between gap-4 h-[52px] md:h-[52px]">

    <p class="text-[.64rem] text-[var(--color-muted)] whitespace-nowrap" aria-live="polite">
      <span id="looks-total">—</span> looks
    </p>

    <!-- Filtres desktop -->
    <div class="hidden md:flex gap-1.5 flex-1 justify-center" role="group" aria-label="Filtrer par catégorie">
      <?php foreach ($cats as $val => $label): ?>
        <button data-filter="<?= htmlspecialchars($val) ?>" class="filter-pill<?= $val==='tous' ? ' active' : '' ?>" aria-pressed="<?= $val==='tous'?'true':'false' ?>" type="button">
          <?= htmlspecialchars($label) ?>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="flex items-center gap-3">
      <!-- Toggle mobile -->
      <button id="looks-filter-toggle" class="filter-pill md:hidden" aria-expanded="false" aria-controls="looks-filter-mobile" type="button">Filtres</button>

      <!-- Tri -->
      <div class="relative">
        <button id="looks-sort-btn" class="flex items-center gap-1 text-[.66rem] text-[var(--color-muted)] hover:text-[var(--color-text)] transition-colors cursor-pointer" aria-expanded="false" type="button">
          <span id="looks-sort-label">Populaires</span> <span>▾</span>
        </button>
        <ul id="looks-sort-menu" class="hidden absolute right-0 top-7 z-50 bg-white min-w-40 border border-[var(--color-border)] shadow-[0_12px_40px_rgba(0,0,0,.09)]" role="listbox">
          <?php foreach (['popularite'=>'Populaires','recents'=>'Récents','alpha-asc'=>'A → Z'] as $sv=>$sl): ?>
            <li data-sort="<?= $sv ?>" role="option" class="looks-sort-opt text-[.70rem] text-[var(--color-muted)] px-5 py-3 cursor-pointer hover:bg-[var(--color-warm)] hover:text-[var(--color-text)]<?= $sv==='popularite' ? ' font-medium text-[var(--color-text)]' : '' ?>">
              <?= htmlspecialchars($sl) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

  </div>
</div>

<!-- Filtres mobile déroulants -->
<div id="looks-filter-mobile" class="hidden flex-wrap gap-2 px-[5vw] py-3 border-b border-[var(--color-border)] bg-[var(--color-cream)]" role="group">
  <?php foreach ($cats as $val => $label): ?>
    <button data-filter="<?= htmlspecialchars($val) ?>" class="filter-pill<?= $val==='tous' ? ' active' : '' ?>" aria-pressed="<?= $val==='tous'?'true':'false' ?>" type="button">
      <?= htmlspecialchars($label) ?>
    </button>
  <?php endforeach; ?>
</div>

<!-- ── Grille looks ── -->
<section class="max-w-[1280px] mx-auto px-[5vw] py-14 pb-24" aria-labelledby="looks-page-title">
  <h2 id="looks-page-title" class="sr-only">Catalogue des looks</h2>

  <div id="looks-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 md:gap-6" role="list" aria-label="Grille des looks">
    <?php for ($i = 0; $i < 8; $i++): ?><div class="skeleton" aria-hidden="true"></div><?php endfor; ?>
  </div>

  <p id="looks-empty" class="hidden text-center py-24 text-[.875rem] text-[var(--color-muted)]" role="status">Aucun look pour ce filtre.</p>

  <!-- Pagination -->
  <nav id="looks-pagination" class="flex items-center justify-center gap-2 mt-16 flex-wrap" aria-label="Pagination">
    <button id="pg-prev" class="page-btn" disabled>←</button>
    <div id="pg-numbers" class="flex gap-1.5 flex-wrap justify-center" role="list"></div>
    <button id="pg-next" class="page-btn">→</button>
  </nav>
</section>

<?php require_once __DIR__ . '/../partials/cta-style-finder.php'; ?>

<!-- JS inline (IIFE, même pattern que l'original) -->
<script>
(function () {
  const state = { categorie: 'tous', sort: 'popularite', page: 1, perPage: 16 };

  const grid      = document.getElementById('looks-grid');
  const empty     = document.getElementById('looks-empty');
  const totalEl   = document.getElementById('looks-total');
  const pgPrev    = document.getElementById('pg-prev');
  const pgNext    = document.getElementById('pg-next');
  const pgNumbers = document.getElementById('pg-numbers');
  const sortBtn   = document.getElementById('looks-sort-btn');
  const sortLabel = document.getElementById('looks-sort-label');
  const sortMenu  = document.getElementById('looks-sort-menu');
  const filterToggle = document.getElementById('looks-filter-toggle');
  const filterMobile = document.getElementById('looks-filter-mobile');

  let totalPages = 1;

  async function load() {
    grid.innerHTML = Array(8).fill('<div class="skeleton" aria-hidden="true"></div>').join('');
    empty.classList.add('hidden');
    const p = new URLSearchParams({ categorie: state.categorie, sort: state.sort, page: state.page, per_page: state.perPage });
    try {
      const res  = await fetch('/api/looks?' + p);
      const data = await res.json();
      totalPages = Math.ceil((data.total ?? 0) / state.perPage) || 1;
      if (totalEl) totalEl.textContent = data.total ?? 0;
      grid.innerHTML = '';
      if (!data.items?.length) { empty.classList.remove('hidden'); }
      else { data.items.forEach(look => { const li = document.createElement('div'); li.setAttribute('role','listitem'); li.innerHTML = renderCard(look); grid.appendChild(li); }); }
      renderPagination();
    } catch (e) { console.error('Erreur looks:', e); }
  }

  function renderCard(look) {
    const img    = look.photo ? `<img class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-[1.06]" src="/assets/img/${esc(look.photo)}" alt="${esc(look.titre)}" loading="lazy">` : `<div class="w-full h-full bg-[var(--color-cream-alt)]"></div>`;
    const badge  = look.categorie ? `<span class="absolute bottom-0 left-0 eyebrow text-white bg-[var(--color-gold)] px-2 py-1 group-hover:opacity-0 transition-opacity">${esc(look.categorie)}</span>` : '';
    const celeb  = look.celebrity_nom ? `<div class="text-[.56rem] tracking-[.22em] uppercase text-[var(--color-gold)] mb-1">${esc(look.celebrity_nom)}</div>` : '';
    const celebB = look.celebrity_nom ? `<div class="text-[.58rem] tracking-[.16em] uppercase text-[var(--color-muted)] mb-1">${esc(look.celebrity_nom)}</div>` : '';
    return `<a href="/looks/${look.id}" class="group block no-underline text-inherit" aria-label="${esc(look.titre)}">
      <div class="relative overflow-hidden aspect-[3/4] bg-[var(--color-cream-alt)]">
        ${img}
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/82 to-transparent flex flex-col justify-end p-4">
          ${celeb}<div class="text-white font-semibold leading-tight" style="font-family:var(--font-heading); font-size:1rem">${esc(look.titre)}</div>
          <div class="mt-3 text-[.58rem] tracking-[.18em] uppercase text-white/55 flex items-center gap-2">Voir le look <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></div>
        </div>
        ${badge}
      </div>
      <div class="pt-3">
        ${celebB}
        <div class="font-medium truncate" style="font-family:var(--font-heading); font-size:.95rem; color:var(--color-text)">${esc(look.titre)}</div>
      </div>
    </a>`;
  }

  function renderPagination() {
    pgPrev.disabled = state.page <= 1;
    pgNext.disabled = state.page >= totalPages;
    pgNumbers.innerHTML = '';
    pagRange(state.page, totalPages).forEach(p => {
      if (p === '…') { const s = document.createElement('span'); s.className = 'text-[var(--color-faint)] px-1'; s.textContent = '…'; pgNumbers.appendChild(s); return; }
      const btn = document.createElement('button');
      btn.className = 'page-btn' + (p === state.page ? ' active' : '');
      btn.textContent = p;
      btn.setAttribute('role', 'listitem');
      btn.addEventListener('click', () => { state.page = p; load(); scrollTo(0,0); });
      pgNumbers.appendChild(btn);
    });
  }

  function pagRange(cur, total) {
    if (total <= 7) return Array.from({length:total},(_,i)=>i+1);
    if (cur <= 4)   return [1,2,3,4,5,'…',total];
    if (cur >= total-3) return [1,'…',total-4,total-3,total-2,total-1,total];
    return [1,'…',cur-1,cur,cur+1,'…',total];
  }

  function esc(str) { return String(str??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

  document.querySelectorAll('[data-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('[data-filter]').forEach(b => { b.classList.remove('active'); b.setAttribute('aria-pressed','false'); });
      document.querySelectorAll(`[data-filter="${btn.dataset.filter}"]`).forEach(b => { b.classList.add('active'); b.setAttribute('aria-pressed','true'); });
      state.categorie = btn.dataset.filter; state.page = 1; load();
    });
  });

  filterToggle?.addEventListener('click', () => {
    const open = filterMobile.classList.toggle('hidden') === false;
    filterMobile.classList.toggle('flex', open);
    filterToggle.setAttribute('aria-expanded', open);
  });

  sortBtn?.addEventListener('click', e => { e.stopPropagation(); const open = sortMenu.classList.toggle('hidden') === false; sortBtn.setAttribute('aria-expanded', open); });
  document.querySelectorAll('.looks-sort-opt').forEach(opt => {
    opt.addEventListener('click', () => {
      document.querySelectorAll('.looks-sort-opt').forEach(o => o.classList.remove('font-medium','text-[var(--color-text)]'));
      opt.classList.add('font-medium');
      sortLabel.textContent = opt.textContent.trim();
      sortMenu.classList.add('hidden');
      state.sort = opt.dataset.sort; state.page = 1; load();
    });
  });
  document.addEventListener('click', () => { sortMenu.classList.add('hidden'); });

  pgPrev.addEventListener('click', () => { if(state.page>1){state.page--;load();scrollTo(0,0);} });
  pgNext.addEventListener('click', () => { if(state.page<totalPages){state.page++;load();scrollTo(0,0);} });

  load();
})();
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>