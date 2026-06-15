<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- ── Breadcrumb ── -->
<nav class="breadcrumb-bar" aria-label="Fil d'Ariane">
  <a href="/">Accueil</a><span>/</span>
  <a href="/looks">Looks</a><span>/</span>
  <span class="current"><?= htmlspecialchars($look['titre'] ?? '') ?></span>
</nav>

<!-- ── Contenu principal ── -->
<div class="max-w-[1200px] mx-auto px-8 py-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-start">

  <!-- Photo (sticky desktop) -->
  <div class="md:sticky md:top-20">
    <?php if (!empty($look['photo'])): ?>
      <img class="w-full rounded-2xl block object-cover" src="/<?= htmlspecialchars($look['photo']) ?>" alt="<?= htmlspecialchars($look['titre'] ?? '') ?>">
    <?php else: ?>
      <div class="w-full aspect-[3/4] rounded-2xl bg-[var(--color-cream-alt)] flex items-center justify-center text-[var(--color-muted)] text-sm">Pas de photo</div>
    <?php endif; ?>

    <!-- Méta sous la photo -->
    <div class="mt-5 flex flex-wrap gap-2 items-center">
      <?php if (!empty($look['celebrity_nom'])): ?>
        <a href="/celebrities/<?= htmlspecialchars($look['celebrity_slug'] ?? '') ?>" class="text-sm font-semibold text-[var(--color-text)] no-underline hover:underline">
          <?= htmlspecialchars($look['celebrity_nom']) ?>
        </a>
      <?php endif; ?>
      <?php if (!empty($look['serie_titre'])): ?>
        <a href="/series/<?= htmlspecialchars($look['serie_slug'] ?? '') ?>" class="text-sm font-semibold text-[var(--color-text)] no-underline hover:underline">
          <?= htmlspecialchars($look['serie_titre']) ?>
          <?= !empty($look['personnage']) ? '— ' . htmlspecialchars($look['personnage']) : '' ?>
          <?= !empty($look['saison']) ? ' · Saison ' . (int)$look['saison'] : '' ?>
        </a>
      <?php endif; ?>
      <?php if (!empty($look['categorie'])): ?>
        <span class="text-[.75rem] uppercase tracking-[.07em] px-3 py-1 rounded-full bg-[var(--color-warm)] text-[var(--color-muted)]">
          <?= htmlspecialchars(ucfirst($look['categorie'])) ?>
        </span>
      <?php endif; ?>
      <span class="ml-auto text-[.8rem] text-[var(--color-faint)]">♥ <?= (int)($look['popularite'] ?? 0) ?></span>
    </div>
  </div>

  <!-- Vêtements -->
  <div>
    <h1 class="mb-2" style="font-family:var(--font-heading); font-size:clamp(1.4rem,3vw,2.2rem); font-weight:700; letter-spacing:-.02em; color:var(--color-text)">
      <?= htmlspecialchars($look['titre'] ?? 'Look') ?>
    </h1>

    <?php if (!empty($vetements)): ?>
      <p class="text-sm text-[var(--color-muted)] mb-6"><?= count($vetements) ?> pièce<?= count($vetements)>1?'s':'' ?> dans ce look</p>
      <ul class="flex flex-col gap-4">
        <?php foreach ($vetements as $i => $v): ?>
          <li class="flex items-center gap-4 p-4 rounded-xl bg-[var(--color-warm)] hover:bg-[var(--color-cream-alt)] transition-colors">
            <span class="text-[.70rem] font-bold text-[var(--color-faint)] tracking-[.05em] w-8 flex-shrink-0"><?= str_pad($i+1,2,'0',STR_PAD_LEFT) ?></span>
            <div class="flex-1 min-w-0">
              <div class="text-[.95rem] font-semibold text-[var(--color-text)] mb-0.5"><?= htmlspecialchars($v['nom'] ?? '') ?></div>
              <?php if (!empty($v['marque'])): ?><div class="text-[.80rem] text-[var(--color-muted)]"><?= htmlspecialchars($v['marque']) ?></div><?php endif; ?>
              <?php if (!empty($v['prix'])): ?><div class="text-[.85rem] font-semibold text-[var(--color-text)] mt-1"><?= htmlspecialchars($v['prix']) ?></div><?php endif; ?>
            </div>
            <a href="#" class="flex-shrink-0 px-4 py-2 rounded-full border border-[var(--color-text)] text-[.80rem] no-underline text-[var(--color-text)] hover:bg-[var(--color-text)] hover:text-white transition-colors">
              Voir sur le site
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p class="py-12 text-[.90rem] text-[var(--color-muted)]">Les pièces de ce look seront bientôt disponibles.</p>
    <?php endif; ?>
  </div>

</div>

<!-- ── Looks similaires ── -->
<?php if (!empty($related)): ?>
  <section class="max-w-[1200px] mx-auto px-8 pb-16 pt-10 border-t border-[var(--color-border)]">
    <h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color:var(--color-text)">Looks similaires</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5">
      <?php foreach ($related as $r): ?>
        <a href="/looks/<?= (int)$r['id'] ?>" class="group block no-underline text-inherit rounded-xl overflow-hidden bg-[var(--color-warm)] hover:-translate-y-1 transition-transform duration-200">
          <?php if (!empty($r['photo'])): ?>
            <img src="/<?= htmlspecialchars($r['photo']) ?>" alt="<?= htmlspecialchars($r['titre']??'') ?>" class="w-full aspect-[3/4] object-cover" loading="lazy" />
          <?php else: ?>
            <div class="w-full aspect-[3/4] bg-[var(--color-cream-alt)]"></div>
          <?php endif; ?>
          <div class="p-3">
            <?php if (!empty($r['celebrity_nom'])): ?>
              <div class="text-[.70rem] uppercase tracking-[.07em] text-[var(--color-muted)] mb-0.5"><?= htmlspecialchars($r['celebrity_nom']) ?></div>
            <?php endif; ?>
            <div class="text-[.85rem] font-semibold leading-tight" style="color:var(--color-text)"><?= htmlspecialchars($r['titre']??'') ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>