<?php require_once __DIR__ . '/../partials/header.php'; ?>

<?php
$heroTitle    = "Leurs looks.<br><em style=\"font-style:italic; color:var(--color-gold)\">Votre</em><br>garde-robe.";
$heroSubtitle = 'Séries';
$heroCount    = '8 000';
$heroSection  = 'series';
$heroCtaLabel = 'Découvrir les séries';
$heroCtaHref  = '#section-catalogue';
require_once __DIR__ . '/../partials/hero-columns.php';
?>

<!-- ── Catalogue ── -->
<section id="section-catalogue" aria-labelledby="catalogue-title" class="max-w-6xl mx-auto px-6 py-14">

  <header class="text-center mb-8">
    <h2 id="catalogue-title" class="text-3xl font-normal text-[var(--color-text)]" style="font-family:var(--font-heading)">
      Les looks portés dans les séries
    </h2>
    <p class="text-[var(--color-muted)] text-sm mt-2">Retrouvez les tenues de vos personnages favoris</p>
  </header>

  <!-- Contrôles -->
  <div class="flex items-center justify-between mb-8 gap-y-3 flex-wrap md:mb-10" role="toolbar" aria-label="Filtres et tri">

    <p class="flex-1 min-w-fit text-xs text-[var(--color-muted)]" aria-live="polite">
      <span id="series-count">—</span> séries
    </p>

    <?php $cats = ['tous'=>'Tous','drame'=>'Drame','crime'=>'Crime','comédie'=>'Comédie','thriller'=>'Thriller','sci-fi'=>'Sci-Fi','romance'=>'Romance','horreur'=>'Horreur','anime'=>'Anime','action'=>'Action']; ?>

    <div class="hidden md:flex flex-1 justify-center flex-wrap gap-1.5" role="group" aria-label="Filtrer par genre">
      <?php foreach ($cats as $val => $label): ?>
        <button data-filter="<?= htmlspecialchars($val) ?>" class="filter-pill<?= $val==='tous' ? ' active' : '' ?>" aria-pressed="<?= $val==='tous'?'true':'false' ?>" type="button">
          <?= htmlspecialchars($label) ?>
        </button>
      <?php endforeach; ?>
    </div>

    <button id="filter-toggle" class="filter-pill md:hidden" aria-expanded="false" aria-controls="filter-menu" type="button">Filtres</button>
    <div id="filter-menu" class="hidden w-full mb-4 pb-4 border-b border-[var(--color-border)] md:hidden flex-wrap gap-2" role="group">
      <?php foreach ($cats as $val => $label): ?>
        <button data-filter="<?= htmlspecialchars($val) ?>" class="filter-pill<?= $val==='tous' ? ' active' : '' ?>" aria-pressed="<?= $val==='tous'?'true':'false' ?>" type="button">
          <?= htmlspecialchars($label) ?>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="hidden md:flex flex-1 justify-end relative">
      <button id="sort-btn" class="flex items-center gap-1 text-xs text-[var(--color-muted)] hover:text-[var(--color-text)] transition-colors cursor-pointer whitespace-nowrap" aria-expanded="false" type="button">
        <span id="sort-label">Populaires</span> <span>▾</span>
      </button>
      <ul id="sort-menu" class="hidden absolute right-0 top-6 z-50 bg-white min-w-36 shadow-[0_8px_32px_rgba(0,0,0,.07)]" role="listbox">
        <?php foreach (['popularite'=>'Populaires','recents'=>'Récents','alpha-asc'=>'A → Z','alpha-desc'=>'Z → A','looks'=>'Nb de looks'] as $sv=>$sl): ?>
          <li data-sort="<?= $sv ?>" role="option" class="text-xs text-[var(--color-muted)] px-5 py-2.5 hover:text-[var(--color-text)] hover:bg-[var(--color-warm)] cursor-pointer whitespace-nowrap<?= $sv==='popularite' ? ' font-medium text-[var(--color-text)]' : '' ?>">
            <?= htmlspecialchars($sl) ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </div>

  <!-- Grille -->
  <div id="series-grid" class="grid grid-cols-2 md:grid-cols-4 gap-6" role="list" aria-label="Grille des séries">
    <?php for ($i = 0; $i < 8; $i++): ?><div class="skeleton" role="listitem" style="aspect-ratio:2/3"></div><?php endfor; ?>
  </div>

  <p id="serie-empty" class="hidden text-[var(--color-muted)] text-sm text-center py-16">Aucune série pour ce filtre.</p>

  <div id="pagination" class="flex items-center justify-center gap-2 mt-12 flex-wrap">
    <button id="page-prev" class="page-btn" disabled>←</button>
    <div id="page-numbers" class="flex gap-1.5 flex-wrap justify-center"></div>
    <button id="page-next" class="page-btn">→</button>
  </div>

</section>

<?php
$ctaTitle    = 'Identifiez un look de série par photo';
$ctaSubtitle = 'Uploadez une capture d\'écran, notre IA retrouve le vêtement exact';
require_once __DIR__ . '/../partials/cta-style-finder.php';
?>

<script src="/js/series.js" defer></script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>