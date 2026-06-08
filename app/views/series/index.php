<?php require_once __DIR__ . '/../partials/header.php'; ?>

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

  .hero-img {
    width:           100%;
    display:         block;
    object-fit:      cover;
    object-position: center top;
    filter:          saturate(.5) brightness(.68) contrast(1.08);
    pointer-events:  none;
    user-select:     none;
  }

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

  .filter-btn {
    font-family:    var(--font-sans);
    font-size:      .7rem;
    letter-spacing: .04em;
    padding:        .28rem .9rem;
    cursor:         pointer;
    border:         1px solid transparent;
    border-radius:  999px;
    color:          var(--color-muted);
    background:     transparent;
    transition:     color .2s, border-color .2s, background .2s;
    line-height:    1.6;
    white-space:    nowrap;
  }
  .filter-btn:hover  { color: var(--color-text); border-color: var(--color-border); }
  .filter-btn.active { color: var(--color-text); border-color: var(--color-text); background: transparent; }

  .page-btn {
    font-family:    var(--font-sans);
    font-size:      .68rem;
    letter-spacing: .08em;
    padding:        .3rem .65rem;
    border:         1px solid var(--color-border);
    background:     transparent;
    color:          var(--color-muted);
    cursor:         pointer;
    transition:     all .15s;
  }
  .page-btn:hover    { color: var(--color-text); border-color: var(--color-text); }
  .page-btn.active   { border-color: var(--color-text); background: var(--color-text); color: var(--color-cream); }
  .page-btn:disabled { opacity: .3; cursor: default; pointer-events: none; }

  .skeleton {
    background:      linear-gradient(90deg, #f0ede8 25%, #e8e4dd 50%, #f0ede8 75%);
    background-size: 800px 100%;
    animation:       shimmer 1.4s infinite;
    aspect-ratio:    2 / 3;
  }

  .serie-card .serie-img {
    transition: transform .4s ease;
  }
  .serie-card:hover .serie-img {
    transform: scale(1.05);
  }
  .serie-card .serie-arrow {
    color:          var(--color-faint);
    transition:     color .2s;
    font-family:    var(--font-sans);
    font-size:      .65rem;
    letter-spacing: .18em;
    text-transform: uppercase;
  }
  .serie-card:hover .serie-arrow { color: var(--color-text); }

  .looks-count {
    display:        inline-flex;
    align-items:    center;
    gap:            .3em;
    font-family:    var(--font-sans);
    font-size:      .62rem;
    font-weight:    600;
    letter-spacing: .12em;
    text-transform: uppercase;
    color:          #fff;
    background:     #b8982a;
    padding:        .18em .55em;
    border-radius:  2px;
  }
</style>


<!-- HERO DESKTOP -->
<section
  id="hero-series"
  aria-label="Galerie des séries en vedette"
  class="hidden md:flex relative overflow-hidden hero-vignette hero-grain"
  style="height: calc(100vh - 40px); background: var(--color-text)"
>
  <div class="absolute inset-0 flex gap-1.5 pointer-events-none select-none" aria-hidden="true">

    <div class="flex-1 overflow-hidden" style="margin-top: -10%; height: 120%">
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

  <!-- Overlay éditorial -->
  <div class="absolute inset-0 z-20 flex flex-col items-center justify-center pointer-events-none select-none">
    <p class="animate-fu-1 font-body" style="font-size:.6rem; font-weight:500; letter-spacing:.42em; text-transform:uppercase; color:var(--color-gold); margin-bottom:1.6rem">
      Repérer · Identifier · Porter
    </p>

    <h1 class="font-heading animate-fu-2 text-center" style="font-size:clamp(4rem,11vw,9.5rem); font-weight:600; line-height:.88; letter-spacing:-.015em; color:var(--color-cream)">
      Leurs looks.<br>
      <em style="font-style:italic; color:var(--color-gold)">Votre</em><br>
      garde-robe.
    </h1>

    <div class="animate-fu-3 flex items-center gap-5" style="margin-top:2rem">
      <span style="display:block; width:44px; height:1px; background:var(--color-gold); opacity:.35"></span>
      <p class="font-body" style="font-size:.64rem; font-weight:300; letter-spacing:.26em; text-transform:uppercase; color:var(--color-cream); opacity:.32">Séries</p>
      <span style="display:block; width:44px; height:1px; background:var(--color-gold); opacity:.35"></span>
    </div>

    <div class="animate-fu-4 pointer-events-auto" style="margin-top:2.5rem">
      <a href="#section-catalogue" class="hero-btn-primary" aria-label="Découvrir le catalogue des séries">
        Découvrir les séries
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
          <path d="M12 5v14M5 12l7 7 7-7"/>
        </svg>
      </a>
    </div>

    <p class="animate-fu-5 font-body" style="margin-top:1.8rem; font-size:.57rem; font-weight:300; letter-spacing:.32em; text-transform:uppercase; color:var(--color-gold); opacity:.38">
      +8 000 looks référencés
    </p>
  </div>

  <p class="absolute bottom-5 left-5 z-20 animate-fu-5 font-body" style="font-size:.5rem; letter-spacing:.28em; text-transform:uppercase; color:var(--color-cream); opacity:.18" aria-hidden="true">© <?= date('Y') ?></p>
  <p class="absolute bottom-5 right-5 z-20 animate-fu-5 text-right font-body" style="font-size:.5rem; letter-spacing:.28em; text-transform:uppercase; color:var(--color-cream); opacity:.18" aria-hidden="true">Mode · Séries · Culture</p>
</section>


<!-- HERO MOBILE -->
<div aria-label="Galerie mobile des séries" class="flex md:hidden flex-col bg-cream" style="min-height: calc(100vh - 40px)">
  <div class="w-full overflow-hidden">
    <img
      src="/assets/img/<?= htmlspecialchars($col1[0]['photo'] ?? 'series/default.webp') ?>"
      alt="<?= htmlspecialchars($col1[0]['nom'] ?? 'Série') ?>"
      class="w-full h-full object-cover object-top"
      loading="eager"
    />
  </div>
  <div class="grid grid-cols-2" role="list" aria-label="Autres séries">
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


<!-- SECTION CATALOGUE -->
<section id="section-catalogue" aria-labelledby="catalogue-title" class="max-w-6xl mx-auto px-6 py-14">

  <header class="text-center mb-8">
    <h2 id="catalogue-title" class="font-heading text-3xl font-normal text-[#1a1a1a]">
      Les looks portés dans les séries
    </h2>
    <p class="text-muted text-sm mt-2 font-body">
      Retrouvez les tenues de vos personnages favoris
    </p>
  </header>

  <!-- Barre de contrôles -->
  <div
    id="catalogue-controls"
    class="flex items-center justify-between mb-8 gap-y-3 flex-wrap md:mb-10"
    role="toolbar"
    aria-label="Filtres et tri du catalogue"
  >
    <div class="flex-1 min-w-fit" aria-live="polite" aria-atomic="true">
      <p class="text-xs text-muted font-body">
        <span id="series-count">—</span> séries
      </p>
    </div>

    <?php
      $cats = [
        'tous'          => 'Tous',
        'drame'         => 'Drame',
        'crime'         => 'Crime',
        'comédie'       => 'Comédie',
        'thriller'      => 'Thriller',
        'sci-fi'        => 'Sci-Fi',
        'romance'       => 'Romance',
        'horreur'       => 'Horreur',
        'anime'         => 'Anime',
        'action'        => 'Action',
      ];
    ?>

    <!-- Filtres desktop -->
    <div class="hidden md:flex flex-1 justify-center" role="group" aria-label="Filtrer par genre">
      <div class="flex gap-1.5 flex-wrap justify-center">
        <?php foreach ($cats as $val => $label): ?>
          <button
            data-filter="<?= htmlspecialchars($val) ?>"
            class="filter-btn<?= $val === 'tous' ? ' active' : '' ?>"
            aria-pressed="<?= $val === 'tous' ? 'true' : 'false' ?>"
            type="button"
          ><?= htmlspecialchars($label) ?></button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Toggle filtres mobile -->
    <button
      id="filter-toggle"
      class="text-xs text-muted px-3 py-1 cursor-pointer border border-border font-body md:hidden hover:border-[#1a1a1a] hover:text-[#1a1a1a] transition-colors duration-200"
      aria-expanded="false"
      aria-controls="filter-menu"
      type="button"
    >Filtres</button>

    <div id="filter-menu" class="hidden w-full mb-4 pb-4 border-b border-border md:hidden" role="group">
      <div class="flex flex-wrap gap-2">
        <?php foreach ($cats as $val => $label): ?>
          <button
            data-filter="<?= htmlspecialchars($val) ?>"
            class="filter-btn<?= $val === 'tous' ? ' active' : '' ?>"
            aria-pressed="<?= $val === 'tous' ? 'true' : 'false' ?>"
            type="button"
          ><?= htmlspecialchars($label) ?></button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Tri desktop -->
    <div class="hidden md:flex flex-1 justify-end">
      <div class="relative flex justify-end">
        <button
          id="sort-btn"
          class="text-xs text-muted flex items-center gap-1 hover:text-[#1a1a1a] transition-colors cursor-pointer whitespace-nowrap font-body"
          aria-expanded="false"
          type="button"
        >
          <span id="sort-label">Populaires</span>
          <span aria-hidden="true">▾</span>
        </button>
        <ul
          id="sort-menu"
          class="hidden absolute right-0 top-6 z-50 bg-white min-w-36"
          role="listbox"
          style="box-shadow: 0 8px 32px rgba(0,0,0,.07)"
        >
          <?php foreach ([
            'popularite' => 'Populaires',
            'recents'    => 'Récents',
            'alpha-asc'  => 'A → Z',
            'alpha-desc' => 'Z → A',
            'looks'      => 'Nb de looks',
          ] as $sv => $sl): ?>
            <li
              data-sort="<?= $sv ?>"
              role="option"
              class="sort-opt text-xs text-muted px-5 py-2.5 hover:text-[#1a1a1a] hover:bg-[#f8f6f1] cursor-pointer whitespace-nowrap font-body<?= $sv === 'popularite' ? ' text-[#1a1a1a] font-medium' : '' ?>"
            ><?= htmlspecialchars($sl) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>

  <!-- Grille séries -->
  <div
    id="series-grid"
    class="grid grid-cols-2 md:grid-cols-4 gap-6"
    role="list"
    aria-label="Grille des séries"
  >
    <?php for ($i = 0; $i < 8; $i++): ?>
      <div class="skeleton rounded-none" role="listitem"></div>
    <?php endfor; ?>
  </div>

  <p id="serie-empty" class="hidden text-muted text-sm text-center py-16 font-body">
    Aucune série pour ce filtre.
  </p>

  <!-- Pagination -->
  <div id="pagination" class="flex items-center justify-center gap-2 mt-12 flex-wrap">
    <button id="page-prev" class="page-btn" disabled>←</button>
    <div id="page-numbers" class="flex gap-1.5 flex-wrap justify-center"></div>
    <button id="page-next" class="page-btn">→</button>
  </div>

</section>


<!-- CTA BANNER -->
<section class="bg-gray-950 text-white text-center py-20 px-6">
  <h2 class="font-playfair text-4xl font-bold">
    Identifiez un look de série par photo
  </h2>
  <p class="text-gray-400 text-sm mt-3">
    Uploadez une capture d'écran, notre IA retrouve le vêtement exact
  </p>
  
    <a href="style-finder.html"
    class="inline-block mt-8 bg-white text-gray-900 text-xs font-bold tracking-widest uppercase px-8 py-4 hover:bg-gray-300 transition-colors duration-200"
  >
    UTILISER LE STYLE FINDER →
  </a>
</section>


<script src="/js/series.js" defer></script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>