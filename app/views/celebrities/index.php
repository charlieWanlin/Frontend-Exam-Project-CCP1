<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- ════════════════════════════════════════════════════
     STYLES INLINE — animations + états filtres
     (identiques aux keyframes du output.css original)
════════════════════════════════════════════════════ -->
<style>
  @keyframes scrollUp {
    from { transform: translateY(0); }
    to   { transform: translateY(-50%); }
  }
  @keyframes scrollDown {
    from { transform: translateY(-50%); }
    to   { transform: translateY(0); }
  }
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes shimmer {
    0%   { background-position: -400px 0; }
    100% { background-position:  400px 0; }
  }

  .animate-scroll-up-slow  { animation: scrollUp   65s linear infinite; }
  .animate-scroll-up-fast  { animation: scrollUp   38s linear infinite; }
  .animate-scroll-down-med { animation: scrollDown 50s linear infinite; }

  .animate-fu-1 { animation: fadeUp .9s .15s ease both; }
  .animate-fu-2 { animation: fadeUp .9s .4s  ease both; }
  .animate-fu-3 { animation: fadeUp .8s .7s  ease both; }
  .animate-fu-4 { animation: fadeUp .8s .95s ease both; }
  .animate-fu-5 { animation: fadeUp .8s 1.2s ease both; }

  /* ── Images colonnes hero ── */
  .hero-img {
    width:           100%;
    display:         block;
    object-fit:      cover;
    object-position: center top;
    filter:          saturate(.5) brightness(.68) contrast(1.08);
    pointer-events:  none;
    user-select:     none;
  }

  /* ── Vignette hero ── */
  .hero-vignette::before {
    content:        '';
    position:       absolute;
    inset:          0;
    z-index:        10;
    pointer-events: none;
    background:
      linear-gradient(to bottom, #1a1a1a 0%, transparent 14%, transparent 86%, #1a1a1a 100%),
      linear-gradient(to right,  #1a1a1a 0%, transparent 6%,  transparent 94%, #1a1a1a 100%);
  }

  /* ── Grain hero ── */
  .hero-grain::after {
    content:          '';
    position:         absolute;
    inset:            0;
    z-index:          11;
    pointer-events:   none;
    opacity:          .045;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.88' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-size:  180px 180px;
  }

  /* ── Bouton hero ── */
  .hero-btn-primary {
    display:         inline-flex;
    align-items:     center;
    gap:             .6rem;
    padding:         .85rem 2.2rem;
    background:      var(--color-gold);
    color:           #fff;
    font-family:     var(--font-sans);
    font-size:       .62rem;
    font-weight:     500;
    letter-spacing:  .2em;
    text-transform:  uppercase;
    text-decoration: none;
    border:          none;
    cursor:          pointer;
    transition:      background .2s;
  }
  .hero-btn-primary:hover { background: var(--color-gold-hover); }

  /* ── Filtres catégories ── */
  .filter-btn {
    font-family:    var(--font-sans);
    font-size:      .7rem;
    letter-spacing: .04em;
    padding:        .25rem .75rem;
    cursor:         pointer;
    border:         1px solid transparent;
    color:          var(--color-muted);
    background:     transparent;
    transition:     color .2s, border-color .2s;
    line-height:    1.6;
  }
  .filter-btn:hover,
  .filter-btn.active {
    color:        var(--color-text);
    border-color: var(--color-border);
  }

  /* ── Filtres alphabétiques ── */
  .letter-btn {
    font-family:    var(--font-sans);
    font-size:      .62rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding:        .2rem .4rem;
    cursor:         pointer;
    background:     none;
    border:         none;
    border-bottom:  1px solid transparent;
    color:          var(--color-muted);
    transition:     color .15s, border-color .15s;
  }
  .letter-btn:hover { color: var(--color-text); }
  .letter-btn.active {
    color:         var(--color-gold);
    border-bottom: 1px solid var(--color-gold);
  }

  /* ── Skeleton loader ── */
  .skeleton {
    background:      linear-gradient(90deg, #f0ede8 25%, #e8e4dd 50%, #f0ede8 75%);
    background-size: 800px 100%;
    animation:       shimmer 1.4s infinite;
    aspect-ratio:    3 / 4;
  }

  /* ── CTA Banner ── */
  .cta-btn {
    display:         inline-block;
    border:          1px solid #fff;
    color:           #fff;
    background:      none;
    padding:         .75rem 2.5rem;
    font-family:     var(--font-sans);
    font-size:       .62rem;
    font-weight:     500;
    letter-spacing:  .18em;
    text-transform:  uppercase;
    text-decoration: none;
    transition:      background .2s, color .2s;
    margin-top:      2rem;
  }
  .cta-btn:hover { background: #fff; color: var(--color-dark); }

  /* ── Card célébrité ── */
  .celeb-card .celeb-img {
    transition: transform .4s ease;
  }
  .celeb-card:hover .celeb-img {
    transform: scale(1.05);
  }
  .celeb-card .celeb-arrow {
    color:      var(--color-faint);
    transition: color .2s;
    font-family: var(--font-sans);
    font-size:   .65rem;
    letter-spacing: .18em;
    text-transform: uppercase;
  }
  .celeb-card:hover .celeb-arrow { color: var(--color-text); }
</style>


<!-- ════════════════════════════════════════════════════
     HERO — DESKTOP (colonnes défilantes)
════════════════════════════════════════════════════ -->
<section
  id="hero-celebrities"
  aria-label="Galerie des célébrités en vedette"
  class="hidden md:flex relative overflow-hidden hero-vignette hero-grain"
  style="height: calc(100vh - 40px); background: var(--color-text)"
>
  <!-- ── Colonnes défilantes ── -->
  <div
    class="absolute inset-0 flex gap-1.5 pointer-events-none select-none"
    aria-hidden="true"
  >
    <!-- Col 1 — monte lentement -->
    <div class="flex-1 overflow-hidden" style="margin-top: -10%; height: 110%">
      <div class="flex flex-col gap-1.5 w-full animate-scroll-up-slow">
        <?php foreach (array_merge($col1, $col1) as $img): ?>
          <img
            src="/assets/img/<?= htmlspecialchars($img['photo']) ?>"
            alt="<?= htmlspecialchars($img['nom']) ?>"
            class="hero-img"
            style="aspect-ratio: 2/3"
            loading="lazy"
          />
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Col 2 — plus large, monte vite -->
    <div class="overflow-hidden h-full" style="flex: 1.4">
      <div class="flex flex-col gap-1.5 w-full animate-scroll-down-med">
        <?php foreach (array_merge($col2, $col2) as $img): ?>
          <img
            src="/assets/img/<?= htmlspecialchars($img['photo']) ?>"
            alt="<?= htmlspecialchars($img['nom']) ?>"
            class="hero-img"
            style="aspect-ratio: 3/4"
            loading="lazy"
          />
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Col 3 — descend -->
    <div class="flex-1 overflow-hidden" style="margin-bottom: -10%; height: 110%">
      <div class="flex flex-col gap-1.5 w-full animate-scroll-up-slow">
        <?php foreach (array_merge($col3, $col3) as $img): ?>
          <img
            src="/assets/img/<?= htmlspecialchars($img['photo']) ?>"
            alt="<?= htmlspecialchars($img['nom']) ?>"
            class="hero-img"
            style="aspect-ratio: 4/5"
            loading="lazy"
          />
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- ── Overlay éditorial ── -->
  <div
    class="absolute inset-0 z-20 flex flex-col items-center justify-center pointer-events-none select-none"
  >
    <p
      class="animate-fu-1 font-body"
      style="font-size:.6rem; font-weight:500; letter-spacing:.42em; text-transform:uppercase; color:var(--color-gold); margin-bottom:1.6rem"
    >
      Repérer · Identifier · Porter
    </p>

    <h1
      class="font-heading animate-fu-2 text-center"
      style="font-size:clamp(4rem,11vw,9.5rem); font-weight:600; line-height:.88; letter-spacing:-.015em; color:var(--color-cream)"
    >
      Leur style.<br>
      <em style="font-style:italic; color:var(--color-gold)">Votre</em><br>
      identité.
    </h1>

    <div class="animate-fu-3 flex items-center gap-5" style="margin-top:2rem">
      <span style="display:block; width:44px; height:1px; background:var(--color-gold); opacity:.35"></span>
      <p class="font-body" style="font-size:.64rem; font-weight:300; letter-spacing:.26em; text-transform:uppercase; color:var(--color-cream); opacity:.32">
        Célébrités
      </p>
      <span style="display:block; width:44px; height:1px; background:var(--color-gold); opacity:.35"></span>
    </div>

    <div class="animate-fu-4 pointer-events-auto" style="margin-top:2.5rem">
      <a href="#section-catalogue" class="hero-btn-primary" aria-label="Découvrir le catalogue des célébrités">
        Découvrir les célébrités
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
          <path d="M12 5v14M5 12l7 7 7-7"/>
        </svg>
      </a>
    </div>

    <p
      class="animate-fu-5 font-body"
      style="margin-top:1.8rem; font-size:.57rem; font-weight:300; letter-spacing:.32em; text-transform:uppercase; color:var(--color-gold); opacity:.38"
    >
      +12 000 looks référencés
    </p>
  </div>

  <!-- Coins discrets -->
  <p
    class="absolute bottom-5 left-5 z-20 animate-fu-5 font-body"
    style="font-size:.5rem; letter-spacing:.28em; text-transform:uppercase; color:var(--color-cream); opacity:.18"
    aria-hidden="true"
  >© <?= date('Y') ?></p>
  <p
    class="absolute bottom-5 right-5 z-20 animate-fu-5 text-right font-body"
    style="font-size:.5rem; letter-spacing:.28em; text-transform:uppercase; color:var(--color-cream); opacity:.18"
    aria-hidden="true"
  >Mode · Style · Culture</p>
</section>


<!-- ════════════════════════════════════════════════════
     HERO — MOBILE (grille photos)
════════════════════════════════════════════════════ -->
<div
  aria-label="Galerie mobile des célébrités"
  class="flex md:hidden flex-col bg-cream"
  style="min-height: calc(100vh - 40px)"
>
  <div class="w-full overflow-hidden">
    <img
      src="/assets/img/<?= htmlspecialchars($col1[0]['photo'] ?? 'celebrities/sydney-sweeney.jpg') ?>"
      alt="<?= htmlspecialchars($col1[0]['nom'] ?? 'Célébrité') ?>"
      class="w-full h-full object-cover object-top"
      loading="eager"
    />
  </div>
  <div class="grid grid-cols-2" role="list" aria-label="Autres célébrités">
    <?php foreach (array_slice(array_merge($col2, $col3), 0, 4) as $img): ?>
      <div class="overflow-hidden" role="listitem">
        <img
          src="/assets/img/<?= htmlspecialchars($img['photo']) ?>"
          alt="<?= htmlspecialchars($img['nom']) ?>"
          class="w-full h-full object-cover object-top"
          loading="lazy"
        />
      </div>
    <?php endforeach; ?>
  </div>
</div>


<!-- ════════════════════════════════════════════════════
     SECTION CATALOGUE
════════════════════════════════════════════════════ -->
<section id="section-catalogue" aria-labelledby="catalogue-title" class="max-w-6xl mx-auto px-6 py-14">

  <!-- En-tête -->
  <header id="catalogue-header" class="text-center mb-8">
    <h2 id="catalogue-title" class="font-heading text-3xl font-normal text-[#1a1a1a]">
      Les looks portés par les célébrités
    </h2>
    <p class="text-muted text-sm mt-2 font-body">
      Inspirez-vous des styles de vos stars favorites
    </p>
  </header>

  <!-- ── Barre de contrôles ── -->
  <div
    id="catalogue-controls"
    class="flex items-center justify-between mb-8 gap-y-3 flex-wrap md:mb-14"
    role="toolbar"
    aria-label="Filtres et tri du catalogue"
  >
    <!-- Compteur -->
    <div id="celebs-counter" class="flex-1 min-w-fit" aria-live="polite" aria-atomic="true">
      <p class="text-xs text-muted font-body">
        <span id="celebs-count">—</span> célébrités
      </p>
    </div>

    <!-- Filtres catégories desktop -->
    <?php
      // Aligné sur la maquette `catalog-celebrites.html`
      $cats = [
        'tous' => 'Tous',
        'cinéma' => 'Cinéma',
        'mannequinat' => 'Mannequinat',
        'chant' => 'Chant',
        'youtube' => 'Youtube',
        'influenceur' => 'Influenceur',
      ];
    ?>
    <div
      id="filters-desktop"
      class="hidden md:flex flex-1 justify-center"
      role="group"
      aria-label="Filtrer par catégorie"
    >
      <div class="flex gap-2">
        <?php foreach ($cats as $val => $label): ?>
          <button
            data-filter="<?= htmlspecialchars($val) ?>"
            class="filter-btn <?= $val === 'tous' ? 'active' : '' ?> text-xs <?= $val === 'tous' ? 'text-[#1a1a1a] border border-muted' : 'text-muted' ?> px-3 py-1 cursor-pointer font-body hover:text-gold transition-colors duration-200"
            aria-pressed="<?= $val === 'tous' ? 'true' : 'false' ?>"
            type="button"
          >
            <?= htmlspecialchars($label) ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Bouton filtres mobile -->
    <button
      id="filter-toggle"
      class="text-xs text-muted px-3 py-1 cursor-pointer border border-border font-body md:hidden hover:border-[#1a1a1a] hover:text-[#1a1a1a] transition-colors duration-200"
      aria-expanded="false"
      aria-controls="filter-menu"
      aria-label="Ouvrir les filtres de catégorie"
      type="button"
    >
      Filtres
    </button>

    <!-- Menu filtres mobile (caché par défaut) -->
    <div
      id="filter-menu"
      class="hidden w-full mb-6 pb-6 border-b border-border md:hidden"
      role="group"
      aria-label="Filtres catégorie mobile"
    >
      <div class="flex flex-wrap gap-2">
        <?php foreach ($cats as $val => $label): ?>
          <button
            data-filter="<?= htmlspecialchars($val) ?>"
            class="filter-btn <?= $val === 'tous' ? 'active' : '' ?> text-xs <?= $val === 'tous' ? 'text-[#1a1a1a] border border-muted' : 'text-muted' ?> px-3 py-1 cursor-pointer font-body"
            aria-pressed="<?= $val === 'tous' ? 'true' : 'false' ?>"
            type="button"
          >
            <?= htmlspecialchars($label) ?>
          </button>
        <?php endforeach; ?>
      </div>

      <!-- Tri mobile -->
      <div class="mt-4 pt-4 border-t border-border">
        <div id="sort-dropdown-mobile" class="relative">
          <button
            id="sort-btn-mobile"
            class="text-xs text-muted flex items-center gap-1 hover:text-[#1a1a1a] transition-colors duration-200 cursor-pointer whitespace-nowrap font-body"
            aria-expanded="false"
            aria-controls="sort-menu-mobile"
            aria-label="Trier les célébrités"
            type="button"
          >
            <span id="sort-label-mobile">Populaires</span>
            <span aria-hidden="true" class="text-[20px]">▾</span>
          </button>
          <ul
            id="sort-menu-mobile"
            class="hidden absolute left-0 top-6 z-50 bg-white"
            role="listbox"
            aria-label="Options de tri"
            style="box-shadow: 0 8px 32px rgba(0,0,0,.07)"
          >
            <?php foreach (['recents' => 'Récents', 'alpha-asc' => 'A → Z', 'alpha-desc' => 'Z → A', 'looks' => 'Nb de looks'] as $sv => $sl): ?>
              <li
                data-sort="<?= $sv ?>"
                role="option"
                aria-selected="false"
                class="sort-opt-mobile text-xs text-muted px-5 py-2.5 hover:text-[#1a1a1a] hover:bg-bg-soft cursor-pointer whitespace-nowrap font-body"
              ><?= htmlspecialchars($sl) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>

    <!-- Tri desktop -->
    <div id="sort-desktop" class="hidden md:flex flex-1 justify-end">
      <div id="sort-dropdown" class="relative min-w-32 flex justify-end">
        <button
          id="sort-btn"
          class="text-xs text-muted flex items-center gap-1 hover:text-[#1a1a1a] transition-colors duration-200 cursor-pointer whitespace-nowrap font-body"
          aria-expanded="false"
          aria-controls="sort-menu"
          aria-label="Trier les célébrités"
          type="button"
        >
          <span id="sort-label">Populaires</span>
          <span aria-hidden="true" class="text-[20px]">▾</span>
        </button>
        <ul
          id="sort-menu"
          class="hidden absolute left-9 top-6 z-50 bg-white"
          role="listbox"
          aria-label="Options de tri"
          style="box-shadow: 0 8px 32px rgba(0,0,0,.07)"
        >
          <?php foreach (['recents' => 'Récents', 'alpha-asc' => 'A → Z', 'alpha-desc' => 'Z → A', 'looks' => 'Nb de looks'] as $sv => $sl): ?>
            <li
              data-sort="<?= $sv ?>"
              role="option"
              aria-selected="false"
              class="sort-opt text-xs text-muted px-5 py-2.5 hover:text-[#1a1a1a] hover:bg-bg-soft cursor-pointer whitespace-nowrap font-body"
            ><?= htmlspecialchars($sl) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>

  <!-- ── Grille célébrités ── -->
  <div
    id="celebrities-grid"
    data-page="celebrities-grid"
    class="grid grid-cols-2 md:grid-cols-4 gap-6"
    role="list"
    aria-label="Grille des célébrités"
    aria-live="polite"
  >
    <!-- Skeletons initiaux — remplacés par JS -->
    <?php for ($i = 0; $i < 8; $i++): ?>
      <div class="skeleton rounded-none" role="listitem"></div>
    <?php endfor; ?>
  </div>

  <p id="celeb-empty" class="hidden text-muted text-sm text-center py-16 font-body">
    Aucune célébrité pour ce filtre.
  </p>

  <!-- Load more -->
  <div class="text-center mt-10">
    <button
      id="load-more-celebs"
      class="px-10 py-3 cursor-pointer border border-[#1a1a1a] text-sm font-medium font-body hover:bg-[#1a1a1a] hover:text-[#f2efe8] transition-all duration-200"
      aria-label="Charger plus de célébrités"
      type="button"
    >
      Charger plus
    </button>
  </div>

</section>


<!-- ════════════════════════════════════════════════════
     BANNIÈRE STYLE FINDER
════════════════════════════════════════════════════ -->
<section
  aria-labelledby="style-finder-title"
  class="bg-[#1a1a1a] text-white text-center py-20 px-6"
>
  <h2 id="style-finder-title" class="font-heading text-4xl font-normal text-white">
    Identifiez un look précis par photo
  </h2>
  <p class="text-white/60 text-sm mt-3 font-body">
    Uploadez une photo d'une scène, notre IA retrouve le vêtement exact
  </p>
  <a href="/style-finder" class="cta-btn" aria-label="Utiliser le Style Finder pour identifier un look">
    UTILISER LE STYLE FINDER →
  </a>
</section>


<!-- ════════════════════════════════════════════════════
     JS — Fetch + filtres + tri + pagination
     Consomme GET /api/celebrities?categorie=&sort=&page=&per_page=
════════════════════════════════════════════════════ -->
<script>
(() => {
  /* ── État ── */
  let activeCategorie = 'tous';
  let activeSort      = 'popularite';
  let page = 1;
  const pageSize = 16;

  /* ── Références DOM ── */
  const grid     = document.getElementById('celebrities-grid');
  const empty    = document.getElementById('celeb-empty');
  const countEl  = document.getElementById('celebs-count');
  const sortBtn  = document.getElementById('sort-btn');
  const sortMenu = document.getElementById('sort-menu');
  const sortLabel = document.getElementById('sort-label');
  const filterToggle = document.getElementById('filter-toggle');
  const filterMenu = document.getElementById('filter-menu');
  const sortBtnMobile = document.getElementById('sort-btn-mobile');
  const sortMenuMobile = document.getElementById('sort-menu-mobile');
  const sortLabelMobile = document.getElementById('sort-label-mobile');
  const loadMoreBtn = document.getElementById('load-more-celebs');

  /* ── Template card — identique au HTML original ── */
  function renderCard(c) {
    return `
      <article class="celeb-card cursor-pointer" role="listitem">
        <a href="/celebrities/${c.slug}">
          <div class="overflow-hidden" style="aspect-ratio:3/4; background:#f0ede8">
            <img
              src="/assets/img/${c.photo}"
              alt="${escHtml(c.nom)}"
              class="celeb-img w-full h-full object-cover object-top"
              loading="lazy"
            />
          </div>
          <div class="pt-4 px-1">
            <h3 class="text-sm tracking-wide text-[#1a1a1a] font-body">${escHtml(c.nom)}</h3>
            ${c.categorie ? `<p class="text-xs text-muted font-light mt-1 font-body">${escHtml(c.categorie)}</p>` : ''}
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-border">
              <span class="text-xs tracking-widest uppercase font-body" style="color:var(--color-gold)">${parseInt(c.nb_looks)} looks</span>
              <span class="celeb-arrow">Voir →</span>
            </div>
          </div>
        </a>
      </article>`;
  }

  /* ── Skeletons pendant le chargement ── */
  function showSkeletons(n = 8) {
    grid.innerHTML = Array(n).fill(
      `<div class="skeleton rounded-none" role="listitem"></div>`
    ).join('');
  }

  /* ── Escape HTML minimal ── */
  function escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function buildParams() {
    const params = new URLSearchParams();
    if (activeCategorie !== 'tous') params.set('categorie', activeCategorie);
    if (activeSort) params.set('sort', activeSort);
    params.set('page', String(page));
    params.set('per_page', String(pageSize));
    return params;
  }

  /* ── Fetch principal ── */
  async function fetchCelebrities({ append = false } = {}) {
    if (!append) showSkeletons();
    empty.classList.add('hidden');
    loadMoreBtn?.setAttribute('disabled', 'true');

    try {
      const res  = await fetch(`/api/celebrities?${buildParams().toString()}`);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();

      const list = Array.isArray(data?.items) ? data.items : (Array.isArray(data) ? data : []);
      const total = typeof data?.total === 'number' ? data.total : null;

      if (!list.length && page === 1) {
        grid.innerHTML = '';
        empty.classList.remove('hidden');
        countEl.textContent = '0';
        loadMoreBtn?.classList.add('hidden');
        return;
      }

      if (!append) grid.innerHTML = '';
      grid.insertAdjacentHTML('beforeend', list.map(renderCard).join(''));

      const renderedCount = grid.querySelectorAll('.celeb-card').length;
      countEl.textContent = total !== null ? String(total) : String(renderedCount);

      const hasMore = typeof data?.has_more === 'boolean'
        ? data.has_more
        : (list.length === pageSize);
      if (hasMore) loadMoreBtn?.classList.remove('hidden');
      else loadMoreBtn?.classList.add('hidden');

    } catch (err) {
      grid.innerHTML = `<p class="text-muted text-xs col-span-full text-center py-10 font-body">Une erreur est survenue.</p>`;
      console.error(err);
      loadMoreBtn?.classList.add('hidden');
    } finally {
      loadMoreBtn?.removeAttribute('disabled');
    }
  }

  /* ── Listeners — filtres catégorie ── */
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.filter-btn').forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-pressed', 'false');
      });
      btn.classList.add('active');
      btn.setAttribute('aria-pressed', 'true');
      activeCategorie = btn.dataset.filter || 'tous';
      page = 1;
      fetchCelebrities({ append: false });
    });
  });

  /* ── Toggle filtres mobile ── */
  if (filterToggle && filterMenu) {
    filterToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = !filterMenu.classList.contains('hidden');
      filterMenu.classList.toggle('hidden');
      filterToggle.setAttribute('aria-expanded', String(!isOpen));
    });
  }

  /* ── Tri — toggle menu ── */
  if (sortBtn && sortMenu) {
    sortBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = !sortMenu.classList.contains('hidden');
      sortMenu.classList.toggle('hidden');
      sortBtn.setAttribute('aria-expanded', String(!isOpen));
    });
  }

  document.addEventListener('click', () => {
    sortMenu?.classList.add('hidden');
    sortBtn?.setAttribute('aria-expanded', 'false');
    sortMenuMobile?.classList.add('hidden');
    sortBtnMobile?.setAttribute('aria-expanded', 'false');
    filterMenu?.classList.add('hidden');
    filterToggle?.setAttribute('aria-expanded', 'false');
  });

  sortMenu?.querySelectorAll('.sort-opt').forEach(opt => {
    opt.addEventListener('click', (e) => {
      e.stopPropagation();
      activeSort = opt.dataset.sort;
      sortLabel.textContent = opt.textContent.trim();
      sortMenu.classList.add('hidden');
      sortBtn.setAttribute('aria-expanded', 'false');
      page = 1;
      fetchCelebrities({ append: false });
    });
  });

  /* ── Tri mobile ── */
  if (sortBtnMobile && sortMenuMobile) {
    sortBtnMobile.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = !sortMenuMobile.classList.contains('hidden');
      sortMenuMobile.classList.toggle('hidden');
      sortBtnMobile.setAttribute('aria-expanded', String(!isOpen));
    });

    sortMenuMobile.querySelectorAll('.sort-opt-mobile').forEach(opt => {
      opt.addEventListener('click', (e) => {
        e.stopPropagation();
        activeSort = opt.dataset.sort;
        sortLabelMobile.textContent = opt.textContent.trim();
        sortMenuMobile.classList.add('hidden');
        sortBtnMobile.setAttribute('aria-expanded', 'false');
        page = 1;
        fetchCelebrities({ append: false });
      });
    });
  }

  /* ── Charger plus ── */
  loadMoreBtn?.addEventListener('click', () => {
    page += 1;
    fetchCelebrities({ append: true });
  });

  /* ── Chargement initial ── */
  fetchCelebrities({ append: false });
})();
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>