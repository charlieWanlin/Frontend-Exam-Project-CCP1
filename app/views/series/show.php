<?php require_once __DIR__ . '/../partials/header.php'; ?>

<nav class="breadcrumb-bar" aria-label="Fil d'Ariane">
  <a href="/">Accueil</a><span>/</span>
  <a href="/series">Séries</a><span>/</span>
  <span class="current"><?= htmlspecialchars($serie['nom']) ?></span>
</nav>

<!-- ── Hero profil ── -->
<section class="flex items-stretch max-w-[1280px] mx-auto px-[5vw] min-h-[75vh] bg-[var(--color-cream)]" aria-label="Profil de <?= htmlspecialchars($serie['nom']) ?>">

  <!-- Affiche -->
  <div class="flex-shrink-0 relative overflow-hidden" style="width:clamp(220px,28vw,380px)">
    <?php if (!empty($serie['photo'])): ?>
      <img src="/assets/img/<?= htmlspecialchars($serie['photo']) ?>" alt="Affiche de <?= htmlspecialchars($serie['nom']) ?>" class="absolute inset-0 w-full h-full object-cover object-top" loading="eager" />
    <?php else: ?>
      <div class="absolute inset-0 bg-[var(--color-cream-alt)]"></div>
    <?php endif; ?>
  </div>

  <!-- Infos -->
  <div class="flex-1 flex flex-col justify-center py-14 pl-16">

    <div class="anim-fu-a mb-4">
      <span class="text-[.58rem] font-medium tracking-[.22em] uppercase text-[var(--color-gold)] border border-[var(--color-gold)] px-3 py-1">
        <?= htmlspecialchars(ucfirst($serie['style'] ?? 'Série')) ?>
      </span>
    </div>

    <h1 class="anim-fu-b" style="font-family:var(--font-heading); font-size:clamp(2.4rem,5vw,4.8rem); font-weight:600; line-height:.92; letter-spacing:-.02em; color:var(--color-text); margin:0">
      <?= htmlspecialchars($serie['nom']) ?>
    </h1>

    <div class="gold-rule anim-fu-b"></div>

    <?php
      $descFallback = [
        'drama'   => 'Une série dont les tenues portent autant l\'intrigue que les dialogues — chaque look raconte un personnage.',
        'comédie' => 'Entre scènes cultes et vestiaires décalés, les personnages imposent un style aussi mémorable que leurs répliques.',
        'fantasy' => 'Costumes d\'exception, univers visuels hors du commun — une référence incontournable pour l\'inspiration mode.',
        'thriller'=> 'Des looks épurés au service de l\'intensité — le style au second plan, mais toujours juste.',
        'romance' => 'Des tenues qui incarnent les émotions, du premier regard à la dernière scène.',
      ];
      $desc = !empty($serie['description']) ? $serie['description'] : ($descFallback[strtolower($serie['style'] ?? '')] ?? 'Une série dont le style visuel continue d\'inspirer la mode contemporaine.');
    ?>
    <p class="anim-fu-c mb-8" style="font-family:var(--font-sans); font-size:.875rem; line-height:1.8; color:var(--color-muted); max-width:40ch">
      <?= htmlspecialchars($desc) ?>
    </p>

    <!-- Stats -->
    <div class="anim-fu-c stat-row pt-6 border-t border-[var(--color-border)] mb-8">
      <div class="stat-pill"><span class="val"><?= number_format((int)$serie['nb_looks']) ?></span><span class="lbl">Looks</span></div>
      <div class="stat-sep"></div>
      <div class="stat-pill">
        <span class="val"><?= (int)$serie['popularite'] ?><span style="font-size:.75rem">/100</span></span>
        <span class="lbl">Popularité</span>
      </div>
      <?php if (!empty($saisons)): ?>
        <div class="stat-sep"></div>
        <div class="stat-pill"><span class="val"><?= count($saisons) ?></span><span class="lbl"><?= count($saisons)>1?'Saisons':'Saison' ?></span></div>
      <?php endif; ?>
      <?php if (!empty($personnages)): ?>
        <div class="stat-sep"></div>
        <div class="stat-pill"><span class="val"><?= count($personnages) ?></span><span class="lbl"><?= count($personnages)>1?'Personnages':'Personnage' ?></span></div>
      <?php endif; ?>
    </div>

    <div class="anim-fu-d">
      <a href="#section-looks" class="flex items-center gap-2 text-[.62rem] font-medium tracking-[.2em] uppercase text-[var(--color-gold)] no-underline">
        Voir tous les looks
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
      </a>
    </div>

  </div>
</section>

<!-- ── Looks ── -->
<section id="section-looks" aria-labelledby="looks-title" class="max-w-6xl mx-auto px-6 py-16">

  <header class="mb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <p class="eyebrow mb-2">Garde-robe</p>
      <h2 id="looks-title" style="font-family:var(--font-heading); font-size:clamp(1.6rem,3.5vw,2.4rem); font-weight:600; line-height:1; color:var(--color-text)">
        Les looks de <?= htmlspecialchars($serie['nom']) ?>
      </h2>
    </div>
    <p class="text-[.75rem] text-[var(--color-muted)] shrink-0" aria-live="polite">
      <span id="looks-count">—</span> looks
    </p>
  </header>

  <!-- Filtres + tri -->
  <div class="flex items-center justify-between mb-8 gap-y-3 flex-wrap" role="toolbar" aria-label="Filtres des looks">
    <div class="flex items-center gap-3 flex-wrap">

      <!-- Filtre personnages -->
      <?php if (!empty($personnages)): ?>
        <div class="flex gap-1.5 flex-wrap" role="group" aria-label="Filtrer par personnage">
          <button data-look-perso="tous" class="filter-pill active" aria-pressed="true" type="button">Tous</button>
          <?php foreach ($personnages as $perso): ?>
            <button data-look-perso="<?= htmlspecialchars($perso) ?>" class="filter-pill" aria-pressed="false" type="button">
              <?= htmlspecialchars($perso) ?>
            </button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Filtre saisons -->
      <?php if (!empty($saisons)): ?>
        <select id="saison-select" class="filter-pill appearance-none" aria-label="Filtrer par saison">
          <option value="0">Toutes les saisons</option>
          <?php foreach ($saisons as $s): ?>
            <option value="<?= (int)$s ?>">Saison <?= (int)$s ?></option>
          <?php endforeach; ?>
        </select>
      <?php endif; ?>

    </div>

    <!-- Tri -->
    <div class="relative">
      <button id="looks-sort-btn" class="flex items-center gap-1 text-[.68rem] text-[var(--color-muted)] hover:text-[var(--color-text)] transition-colors cursor-pointer" aria-expanded="false" aria-haspopup="listbox" type="button">
        <span id="looks-sort-label">Populaires</span> <span>▾</span>
      </button>
      <ul id="looks-sort-menu" class="hidden absolute right-0 top-7 z-50 bg-white min-w-36 shadow-[0_8px_32px_rgba(0,0,0,.07)]" role="listbox">
        <?php foreach (['popularite'=>'Populaires','recents'=>'Récents','alpha-asc'=>'A → Z'] as $sv=>$sl): ?>
          <li data-look-sort="<?= $sv ?>" role="option" class="looks-sort-opt text-[.72rem] px-5 py-2.5 cursor-pointer hover:bg-[var(--color-warm)] text-[var(--color-muted)] hover:text-[var(--color-text)]<?= $sv==='popularite' ? ' font-medium' : '' ?>">
            <?= htmlspecialchars($sl) ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <!-- Grille -->
  <div id="looks-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6" role="list" aria-label="Grille des looks">
    <?php for ($i = 0; $i < 8; $i++): ?><div class="skeleton" aria-hidden="true"></div><?php endfor; ?>
  </div>

  <p id="looks-empty" class="hidden text-center py-16 text-[.875rem] text-[var(--color-muted)]">Aucun look pour ce filtre.</p>

  <nav id="looks-pagination" class="flex items-center justify-center gap-2 mt-12 flex-wrap">
    <button id="looks-page-prev" class="page-btn" disabled>←</button>
    <div id="looks-page-numbers" class="flex gap-1.5 flex-wrap justify-center" role="list"></div>
    <button id="looks-page-next" class="page-btn">→</button>
  </nav>

</section>

<?php
$ctaTitle    = 'Vous avez vu un look similaire ?';
$ctaSubtitle = 'Uploadez une photo, notre IA identifie le vêtement exact';
require_once __DIR__ . '/../partials/cta-style-finder.php';
?>

<script>window.SERIE_ID = <?= (int)$serie['id'] ?>;</script>
<script src="/js/serie-show.js" defer></script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>