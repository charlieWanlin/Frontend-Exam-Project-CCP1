<?php
/**
 * Partial : hero colonnes défilantes
 * Variables requises : $col1, $col2, $col3 (arrays d'images), $heroTitle, $heroSubtitle, $heroCount, $heroSection, $heroCtaLabel, $heroCtaHref
 */
?>

<!-- ── Hero desktop — colonnes défilantes ── -->
<section
  id="hero-<?= htmlspecialchars($heroSection) ?>"
  aria-label="Galerie des <?= htmlspecialchars($heroSection) ?> en vedette"
  class="hidden md:flex relative overflow-hidden hero-vignette hero-grain"
  style="height: calc(100vh - 40px); background: var(--color-dark)"
>

  <!-- Colonnes d'images -->
  <div class="absolute inset-0 flex gap-1.5 pointer-events-none select-none" aria-hidden="true">

    <div class="flex-1 overflow-hidden" style="margin-top:-10%; height:120%">
      <div class="flex flex-col gap-1.5 w-full anim-scroll-up-slow">
        <?php foreach (array_merge($col1, $col1) as $img): ?>
          <img src="/assets/img/<?= htmlspecialchars($img['photo']) ?>" alt="<?= htmlspecialchars($img['nom']) ?>" class="hero-img" style="aspect-ratio:2/3" loading="lazy" />
        <?php endforeach; ?>
      </div>
    </div>

    <div class="overflow-hidden h-full" style="flex:1.4">
      <div class="flex flex-col gap-1.5 w-full anim-scroll-down-med">
        <?php foreach (array_merge($col2, $col2) as $img): ?>
          <img src="/assets/img/<?= htmlspecialchars($img['photo']) ?>" alt="<?= htmlspecialchars($img['nom']) ?>" class="hero-img" style="aspect-ratio:3/4" loading="lazy" />
        <?php endforeach; ?>
      </div>
    </div>

    <div class="flex-1 overflow-hidden" style="margin-bottom:-10%; height:110%">
      <div class="flex flex-col gap-1.5 w-full anim-scroll-up-slow">
        <?php foreach (array_merge($col3, $col3) as $img): ?>
          <img src="/assets/img/<?= htmlspecialchars($img['photo']) ?>" alt="<?= htmlspecialchars($img['nom']) ?>" class="hero-img" style="aspect-ratio:4/5" loading="lazy" />
        <?php endforeach; ?>
      </div>
    </div>

  </div>

  <!-- Overlay éditorial -->
  <div class="absolute inset-0 z-20 flex flex-col items-center justify-center pointer-events-none select-none">

    <p class="eyebrow anim-fu-1 mb-6">Repérer · Identifier · Porter</p>

    <h1 class="anim-fu-2 text-center" style="font-family:var(--font-heading); font-size:clamp(4rem,11vw,9.5rem); font-weight:600; line-height:.88; letter-spacing:-.015em; color:var(--color-cream)">
      <?= $heroTitle ?>
    </h1>

    <div class="anim-fu-3 flex items-center gap-5 mt-8">
      <span class="block w-11 h-px bg-[var(--color-gold)] opacity-35"></span>
      <p class="text-[.64rem] font-light tracking-[.26em] uppercase text-[var(--color-cream)] opacity-30" style="font-family:var(--font-sans)"><?= htmlspecialchars($heroSubtitle) ?></p>
      <span class="block w-11 h-px bg-[var(--color-gold)] opacity-35"></span>
    </div>

    <div class="anim-fu-4 pointer-events-auto mt-10">
      <a href="<?= htmlspecialchars($heroCtaHref) ?>" class="btn-gold" aria-label="<?= htmlspecialchars($heroCtaLabel) ?>">
        <?= htmlspecialchars($heroCtaLabel) ?>
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
      </a>
    </div>

    <p class="anim-fu-5 eyebrow mt-7 opacity-35">+<?= htmlspecialchars($heroCount) ?> looks référencés</p>

  </div>

  <!-- Coins date/sous-titre -->
  <p class="absolute bottom-5 left-5 z-20 anim-fu-5 text-[.5rem] tracking-[.28em] uppercase text-[var(--color-cream)] opacity-20" aria-hidden="true">© <?= date('Y') ?></p>
  <p class="absolute bottom-5 right-5 z-20 anim-fu-5 text-[.5rem] tracking-[.28em] uppercase text-[var(--color-cream)] opacity-20 text-right" aria-hidden="true">Mode · Style · Culture</p>

</section>

<!-- ── Hero mobile — grille statique ── -->
<div aria-label="Galerie mobile" class="flex md:hidden flex-col bg-[var(--color-cream)]" style="min-height:calc(100vh - 40px)">
  <div class="w-full overflow-hidden">
    <img
      src="/assets/img/<?= htmlspecialchars($col1[0]['photo'] ?? '') ?>"
      alt="<?= htmlspecialchars($col1[0]['nom'] ?? '') ?>"
      class="w-full h-full object-cover object-top"
      loading="eager"
    />
  </div>
  <div class="grid grid-cols-2">
    <?php foreach (array_slice(array_merge($col2, $col3), 0, 4) as $img): ?>
      <div class="overflow-hidden">
        <img src="/assets/img/<?= htmlspecialchars($img['photo']) ?>" alt="<?= htmlspecialchars($img['nom']) ?>" class="w-full h-full object-cover object-top" loading="lazy" />
      </div>
    <?php endforeach; ?>
  </div>
</div>