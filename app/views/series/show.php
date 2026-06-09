<?php require_once __DIR__ . '/../partials/header.php'; ?>

<style>
  /* ── Animations ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  @keyframes shimmer {
    0%   { background-position: -400px 0; }
    100% { background-position:  400px 0; }
  }
  .fu-1 { animation: fadeUp .6s .08s ease both; }
  .fu-2 { animation: fadeUp .6s .20s ease both; }
  .fu-3 { animation: fadeUp .6s .34s ease both; }
  .fu-4 { animation: fadeUp .6s .48s ease both; }

  /* ══════════════════════════════════════════
     HERO
  ══════════════════════════════════════════ */
  .serie-hero {
    display: flex;
    align-items: stretch;
    gap: 0;
    background: var(--color-cream);
    min-height: 75vh;
    padding: 0 5vw;
    max-width: 1280px;
    margin: 0 auto;
  }

  /* ── Colonne photo ── */
  .serie-hero__img-wrap {
    flex-shrink: 0;
    width: clamp(220px, 28vw, 380px);
    align-self: stretch;
    position: relative;
    overflow: hidden;
  }
  .serie-hero__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
  }
  .serie-hero__img-placeholder {
    position: absolute;
    inset: 0;
    background: #e8e4dd;
  }

  /* ── Colonne infos ── */
  .serie-hero__info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 3.5rem 0 3.5rem 4rem;
  }

  /* ── Badge style ── */
  .style-badge {
    display: inline-block;
    font-family: var(--font-sans);
    font-size: .58rem;
    font-weight: 500;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--color-gold);
    border: 1px solid var(--color-gold);
    padding: .2rem .7rem;
  }

  /* ── Trait doré ── */
  .gold-rule {
    width: 36px;
    height: 1px;
    background: var(--color-gold);
    opacity: .45;
    margin: .7rem 0;
  }

  /* ── Stats ── */
  .stat-row { display: flex; align-items: center; gap: 2.2rem; }
  .stat-pill { display: flex; flex-direction: column; gap: .15rem; }
  .stat-pill .val {
    font-family: var(--font-heading);
    font-size: 1.45rem; font-weight: 600; line-height: 1;
    color: var(--color-text);
  }
  .stat-pill .lbl {
    font-family: var(--font-sans);
    font-size: .56rem; letter-spacing: .18em;
    text-transform: uppercase; color: var(--color-muted);
  }
  .stat-sep { width: 1px; height: 32px; background: var(--color-border); }

  /* ── CTA ── */
  .hero-cta {
    font-family: var(--font-sans); font-size: .62rem; font-weight: 500;
    letter-spacing: .2em; text-transform: uppercase;
    color: var(--color-gold); text-decoration: none;
    display: inline-flex; align-items: center; gap: .5rem;
  }

  /* ── Breadcrumb ── */
  .breadcrumb-bar {
    max-width: 1280px;
    margin: 0 auto;
    padding: 1.4rem 5vw .6rem;
    display: flex;
    align-items: center;
    gap: .5rem;
    font-family: var(--font-sans);
    font-size: .58rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--color-muted);
  }
  .breadcrumb-bar a { color: inherit; text-decoration: none; transition: color .15s; }
  .breadcrumb-bar a:hover { color: var(--color-text); }
  .breadcrumb-bar .current { color: var(--color-text); opacity: .45; }

  /* ── Responsive ── */
  @media (max-width: 767px) {
    .serie-hero {
      flex-direction: column;
      padding: 0;
      min-height: unset;
    }
    .serie-hero__img-wrap {
      width: 100%;
      height: 72vw;
      position: relative;
    }
    .serie-hero__info {
      padding: 2rem 1.5rem 2.5rem;
    }
    .breadcrumb-bar { padding: 1rem 1.5rem .4rem; }
  }
  @media (min-width: 768px) and (max-width: 1024px) {
    .serie-hero__info { padding: 2.5rem 0 2.5rem 2.5rem; }
    .serie-hero__img-wrap { width: clamp(200px, 32vw, 300px); }
  }

  /* ══════════════════════════════════════════
     FILTRES LOOKS
  ══════════════════════════════════════════ */
  .look-filter-btn {
    font-family: var(--font-sans); font-size: .68rem; letter-spacing: .06em;
    padding: .3rem .9rem; border-radius: 999px;
    border: 1px solid transparent;
    color: var(--color-muted); background: transparent;
    cursor: pointer; transition: color .2s, border-color .2s; white-space: nowrap;
  }
  .look-filter-btn:hover  { color: var(--color-text); border-color: var(--color-border); }
  .look-filter-btn.active { color: var(--color-text); border-color: var(--color-text); }

  /* ══════════════════════════════════════════
     LOOK CARD
  ══════════════════════════════════════════ */
  .look-card { position: relative; overflow: hidden; background: #f0ede8; }
  .look-card img {
    width: 100%; height: 100%; object-fit: cover; object-position: center top;
    display: block; transition: transform .4s ease;
  }
  .look-card:hover img { transform: scale(1.04); }
  .look-card-overlay {
    position: absolute; inset: 0; opacity: 0; transition: opacity .3s;
    background: linear-gradient(to top, rgba(26,26,26,.72) 0%, transparent 55%);
    display: flex; align-items: flex-end; padding: 1rem;
  }
  .look-card:hover .look-card-overlay { opacity: 1; }
  .look-card-overlay p {
    color: #fff; font-family: var(--font-sans);
    font-size: .65rem; letter-spacing: .14em; text-transform: uppercase;
  }

  /* ── Skeleton ── */
  .skeleton {
    background: linear-gradient(90deg, #f0ede8 25%, #e8e4dd 50%, #f0ede8 75%);
    background-size: 800px 100%; animation: shimmer 1.4s infinite;
    aspect-ratio: 3 / 4;
  }

  /* ── Pagination ── */
  .page-btn {
    font-family: var(--font-sans); font-size: .68rem; letter-spacing: .08em;
    padding: .3rem .65rem; border: 1px solid var(--color-border);
    background: transparent; color: var(--color-muted);
    cursor: pointer; transition: all .15s;
  }
  .page-btn:hover  { color: var(--color-text); border-color: var(--color-text); }
  .page-btn.active { border-color: var(--color-text); background: var(--color-text); color: var(--color-cream); }
  .page-btn:disabled { opacity: .3; cursor: default; pointer-events: none; }

  /* ── Filtre saison (select discret) ── */
  .saison-select {
    font-family: var(--font-sans); font-size: .68rem; letter-spacing: .06em;
    padding: .3rem .8rem; border-radius: 999px;
    border: 1px solid var(--color-border);
    color: var(--color-muted); background: transparent;
    cursor: pointer; appearance: none;
    transition: color .2s, border-color .2s;
  }
  .saison-select:focus,
  .saison-select:hover { color: var(--color-text); border-color: var(--color-text); outline: none; }
</style>


<!-- ══════════════════════════════════════════════
     FIL D'ARIANE
══════════════════════════════════════════════ -->
<nav class="breadcrumb-bar" aria-label="Fil d'Ariane">
  <a href="/">Accueil</a>
  <span aria-hidden="true">/</span>
  <a href="/series">Séries</a>
  <span aria-hidden="true">/</span>
  <span class="current"><?= htmlspecialchars($serie['nom']) ?></span>
</nav>


<!-- ══════════════════════════════════════════════
     HERO
══════════════════════════════════════════════ -->
<section
  class="serie-hero"
  aria-label="Profil de <?= htmlspecialchars($serie['nom']) ?>"
  style="background: var(--color-cream)"
>

  <!-- Photo -->
  <div class="serie-hero__img-wrap">
    <?php if (!empty($serie['photo'])): ?>
      <img
        src="/assets/img/<?= htmlspecialchars($serie['photo']) ?>"
        alt="Affiche de <?= htmlspecialchars($serie['nom']) ?>"
        class="serie-hero__img"
        loading="eager"
      >
    <?php else: ?>
      <div class="serie-hero__img-placeholder"></div>
    <?php endif; ?>
  </div>

  <!-- Infos -->
  <div class="serie-hero__info">

    <div class="fu-1" style="margin-bottom: .9rem">
      <span class="style-badge">
        <?= htmlspecialchars(ucfirst($serie['style'] ?? 'Série')) ?>
      </span>
    </div>

    <h1
      class="fu-2 font-heading"
      style="font-size: clamp(2.4rem, 5vw, 4.8rem); font-weight: 600; line-height: .92; letter-spacing: -.02em; color: var(--color-text); margin: 0"
    >
      <?= htmlspecialchars($serie['nom']) ?>
    </h1>

    <div class="fu-2 gold-rule"></div>

    <?php
      // Description : on utilise celle en base si elle existe, sinon fallback par style
      $descFallback = [
        'drama'    => 'Une série dont les tenues portent autant l\'intrigue que les dialogues — chaque look raconte un personnage.',
        'comédie'  => 'Entre scènes cultes et vestiaires décalés, les personnages imposent un style aussi mémorable que leurs répliques.',
        'fantasy'  => 'Costumes d\'exception, univers visuels hors du commun — une référence incontournable pour l\'inspiration mode.',
        'thriller' => 'Des looks épurés au service de l\'intensité — le style au second plan, mais toujours juste.',
        'romance'  => 'Des tenues qui incarnent les émotions, du premier regard à la dernière scène.',
      ];
      $style = strtolower($serie['style'] ?? '');
      $desc  = !empty($serie['description'])
               ? $serie['description']
               : ($descFallback[$style] ?? 'Une série dont le style visuel continue d\'inspirer la mode contemporaine.');
    ?>
    <p
      class="fu-3 font-body"
      style="font-size: .875rem; line-height: 1.8; color: var(--color-muted); max-width: 40ch; margin: 0 0 2rem"
    >
      <?= htmlspecialchars($desc) ?>
    </p>

    <!-- Stats -->
    <div
      class="fu-3 stat-row"
      style="padding-top: 1.5rem; border-top: 1px solid var(--color-border); margin-bottom: 2rem"
    >
      <div class="stat-pill">
        <span class="val"><?= number_format((int)$serie['nb_looks']) ?></span>
        <span class="lbl">Looks</span>
      </div>

      <div class="stat-sep" aria-hidden="true"></div>

      <div class="stat-pill">
        <span class="val">
          <?= (int)$serie['popularite'] ?><span style="font-size:.75rem">/100</span>
        </span>
        <span class="lbl">Popularité</span>
      </div>

      <?php if (!empty($saisons)): ?>
        <div class="stat-sep" aria-hidden="true"></div>
        <div class="stat-pill">
          <span class="val"><?= count($saisons) ?></span>
          <span class="lbl"><?= count($saisons) > 1 ? 'Saisons' : 'Saison' ?></span>
        </div>
      <?php endif; ?>

      <?php if (!empty($personnages)): ?>
        <div class="stat-sep" aria-hidden="true"></div>
        <div class="stat-pill">
          <span class="val"><?= count($personnages) ?></span>
          <span class="lbl"><?= count($personnages) > 1 ? 'Personnages' : 'Personnage' ?></span>
        </div>
      <?php endif; ?>
    </div>

    <!-- CTA -->
    <div class="fu-4">
      <a href="#section-looks" class="hero-cta">
        Voir tous les looks
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
          <path d="M12 5v14M5 12l7 7 7-7"/>
        </svg>
      </a>
    </div>

  </div>
</section>


<!-- ══════════════════════════════════════════════
     SECTION LOOKS
══════════════════════════════════════════════ -->
<section id="section-looks" aria-labelledby="looks-title" class="max-w-6xl mx-auto px-6 py-16">

  <!-- En-tête -->
  <header class="mb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <p style="font-family: var(--font-sans); font-size: .58rem; letter-spacing: .28em; text-transform: uppercase; color: var(--color-gold); margin-bottom: .5rem">
        Garde-robe
      </p>
      <h2
        id="looks-title"
        class="font-heading"
        style="font-size: clamp(1.6rem, 3.5vw, 2.4rem); font-weight: 600; line-height: 1; color: var(--color-text)"
      >
        Les looks de <?= htmlspecialchars($serie['nom']) ?>
      </h2>
    </div>
    <p
      id="looks-count-label"
      class="font-body shrink-0"
      style="font-size: .75rem; color: var(--color-muted)"
      aria-live="polite"
      aria-atomic="true"
    >
      <span id="looks-count">—</span> looks
    </p>
  </header>

  <!-- Barre filtres + tri -->
  <div
    class="flex items-center justify-between mb-8 gap-y-3 flex-wrap"
    role="toolbar"
    aria-label="Filtres des looks"
  >
    <div class="flex items-center gap-3 flex-wrap">

      <!-- Filtre par personnage -->
      <?php if (!empty($personnages)): ?>
        <div class="flex gap-1.5 flex-wrap" role="group" aria-label="Filtrer par personnage">
          <button data-look-perso="tous" class="look-filter-btn active" aria-pressed="true" type="button">
            Tous
          </button>
          <?php foreach ($personnages as $perso): ?>
            <button
              data-look-perso="<?= htmlspecialchars($perso) ?>"
              class="look-filter-btn"
              aria-pressed="false"
              type="button"
            >
              <?= htmlspecialchars($perso) ?>
            </button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Filtre par saison -->
      <?php if (!empty($saisons)): ?>
        <div>
          <label for="saison-select" class="sr-only">Filtrer par saison</label>
          <select id="saison-select" class="saison-select" aria-label="Filtrer par saison">
            <option value="0">Toutes les saisons</option>
            <?php foreach ($saisons as $s): ?>
              <option value="<?= (int)$s ?>">Saison <?= (int)$s ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      <?php endif; ?>

    </div>

    <!-- Tri -->
    <div class="relative">
      <button
        id="looks-sort-btn"
        class="font-body flex items-center gap-1 hover:text-[#1a1a1a] transition-colors cursor-pointer whitespace-nowrap"
        style="font-size: .68rem; color: var(--color-muted)"
        aria-expanded="false"
        aria-haspopup="listbox"
        type="button"
      >
        <span id="looks-sort-label">Populaires</span>
        <span aria-hidden="true">▾</span>
      </button>
      <ul
        id="looks-sort-menu"
        class="hidden absolute right-0 top-7 z-50 bg-white"
        role="listbox"
        aria-label="Trier les looks"
        style="min-width: 9rem; box-shadow: 0 8px 32px rgba(0,0,0,.07)"
      >
        <li data-look-sort="popularite" role="option" aria-selected="true"  class="looks-sort-opt font-body font-medium px-5 py-2.5 cursor-pointer hover:bg-[#f8f6f1]" style="font-size:.72rem; color: var(--color-text)">Populaires</li>
        <li data-look-sort="recents"    role="option" aria-selected="false" class="looks-sort-opt font-body px-5 py-2.5 cursor-pointer hover:bg-[#f8f6f1]" style="font-size:.72rem; color: var(--color-muted)">Récents</li>
        <li data-look-sort="alpha-asc"  role="option" aria-selected="false" class="looks-sort-opt font-body px-5 py-2.5 cursor-pointer hover:bg-[#f8f6f1]" style="font-size:.72rem; color: var(--color-muted)">A → Z</li>
      </ul>
    </div>
  </div>

  <!-- Grille -->
  <div
    id="looks-grid"
    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6"
    role="list"
    aria-label="Grille des looks"
  >
    <?php for ($i = 0; $i < 8; $i++): ?>
      <div class="skeleton" role="presentation" aria-hidden="true"></div>
    <?php endfor; ?>
  </div>

  <p id="looks-empty" class="hidden font-body text-center py-16" style="font-size: .875rem; color: var(--color-muted)">
    Aucun look pour ce filtre.
  </p>

  <!-- Pagination -->
  <nav id="looks-pagination" class="flex items-center justify-center gap-2 mt-12 flex-wrap" aria-label="Pagination des looks">
    <button id="looks-page-prev" class="page-btn" disabled aria-label="Page précédente">←</button>
    <div id="looks-page-numbers" class="flex gap-1.5 flex-wrap justify-center" role="list"></div>
    <button id="looks-page-next" class="page-btn" aria-label="Page suivante">→</button>
  </nav>

</section>


<!-- ══════════════════════════════════════════════
     BANNIÈRE STYLE FINDER
══════════════════════════════════════════════ -->
<section
  class="text-center py-20 px-6"
  style="background: #0f0f0f; color: #fff"
  aria-label="Style Finder"
>
  <h2 class="font-heading" style="font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 600">
    Vous avez vu un look similaire ?
  </h2>
  <p style="color: rgba(255,255,255,.45); font-size: .85rem; margin-top: .75rem; font-family: var(--font-body)">
    Uploadez une photo, notre IA identifie le vêtement exact
  </p>
  <a
    href="/style-finder"
    style="
      display: inline-block; margin-top: 2rem;
      background: #fff; color: #0f0f0f;
      font-family: var(--font-sans); font-size: .65rem;
      font-weight: 700; letter-spacing: .2em; text-transform: uppercase;
      text-decoration: none; padding: 1rem 2.5rem;
      transition: background .2s
    "
    onmouseover="this.style.background='#d4d0c8'"
    onmouseout="this.style.background='#fff'"
  >
    Utiliser le Style Finder →
  </a>
</section>


<!-- Variables JS pour le script -->
<script>
  window.SERIE_ID = <?= (int)$serie['id'] ?>;
</script>
<script src="/js/serie-show.js" defer></script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>