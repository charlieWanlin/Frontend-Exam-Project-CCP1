<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- ── Hero vidéo ── -->
<div class="relative overflow-hidden" style="height:100vh; min-height:400px">
  <video autoplay muted loop playsinline class="w-full h-full object-cover">
    <source src="/assets/videos/hero-video.mp4" type="video/mp4" />
  </video>
  <div class="w-full h-10 bg-white absolute bottom-0 left-0"></div>
</div>

<!-- ── Présentation ── -->
<section class="bg-[var(--color-warm)] py-20 md:min-h-screen md:flex md:items-center">
  <div class="w-full px-4 md:px-10 xl:px-16 md:grid md:grid-cols-2 md:gap-16 md:items-center">

    <div class="flex flex-col gap-6">
      <p class="text-xs tracking-widest text-[var(--color-muted)] uppercase hidden md:block">Une nouvelle façon de découvrir votre style</p>
      <h2 class="text-4xl font-normal md:text-5xl xl:text-6xl leading-snug tracking-tight" style="font-family:var(--font-heading)">
        Identifiez le look de <span class="text-[var(--color-gold)] italic">vos stars</span>
      </h2>
      <p class="text-sm text-[var(--color-muted)] font-light leading-loose md:max-w-sm">
        Uploadez une photo de votre tenue ou de celle d'une célébrité, et notre IA vous aidera à trouver des vêtements similaires, à découvrir les marques et à acheter les pièces que vous aimez.
      </p>
      <div class="flex flex-col gap-3 sm:flex-row sm:gap-4 mt-2">
        <a href="/style-finder" class="btn-dark text-center">ANALYSER UNE PHOTO</a>
        <a href="/celebrities" class="inline-block border border-[var(--color-border)] text-[var(--color-muted)] px-8 py-3.5 text-xs tracking-widest hover:border-black hover:text-black transition-colors duration-300 text-center">VOIR DES EXEMPLES</a>
      </div>
      <div class="hidden md:flex gap-12 mt-6 pt-6 border-t border-[var(--color-border)]">
        <?php foreach (['50 000+'=>'Analyses','200'=>'Marques','98%'=>'Précision'] as $val=>$lbl): ?>
          <div>
            <p class="text-xl font-normal <?= $lbl==='Précision' ? 'text-[var(--color-gold)]' : 'text-black' ?>"><?= $val ?></p>
            <p class="text-xs tracking-widest text-[var(--color-muted)] uppercase mt-1.5"><?= $lbl ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Image IA scanner -->
    <div class="mt-12 md:mt-0 relative w-full">
      <img src="/assets/img/landing page/Analyse sydney.png" alt="Scanner IA" class="w-full h-auto object-cover block" />
      <?php
        $tags = [
          ['top-[28%] right-[25%]', '1', 'Chemise',  'right'],
          ['top-[38%] left-[29%]',  '2', 'Brassière','left'],
          ['bottom-[28%] right-[30%]','3','Pantalon', 'right'],
          ['bottom-[8%] right-[26%]', '4','Chaussures','right'],
        ];
        foreach ($tags as [$pos, $num, $label, $side]):
      ?>
        <div class="absolute flex items-center gap-2 <?= $pos ?>">
          <?php if ($side === 'left'): ?>
            <span class="bg-black text-xs text-white p-1.5 whitespace-nowrap"><?= $label ?></span>
          <?php endif; ?>
          <span class="bg-[var(--color-gold)] rounded-full w-6 h-6 flex justify-center items-center text-white text-xs font-medium shrink-0"><?= $num ?></span>
          <?php if ($side === 'right'): ?>
            <span class="bg-black text-xs text-white p-1.5 whitespace-nowrap"><?= $label ?></span>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ── Steps ── -->
<section class="bg-white py-20 px-4 md:px-10 xl:px-16">
  <p class="eyebrow text-center mb-3">Comment ça marche</p>
  <h3 class="text-center text-lg font-normal tracking-widest uppercase mb-14" style="font-family:var(--font-heading)">Simple. Rapide. Gratuit.</h3>
  <div class="grid grid-cols-1 gap-6 md:grid-cols-3 md:gap-8">
    <?php
      $steps = [
        ['01','Uploadez votre photo',     'Importez une photo de la tenue d\'une célébrité, d\'une série, ou de votre propre garde-robe.', '/style-finder','Commencer'],
        ['02','L\'IA détecte chaque pièce','Notre intelligence artificielle identifie chaque vêtement, accessoire et chaussure en quelques secondes.','#','En savoir plus'],
        ['03','Shoppez à votre budget',   'Trouvez les pièces exactes ou des alternatives similaires, adaptées à tous les budgets.','#','Voir les résultats'],
      ];
      foreach ($steps as [$num, $titre, $desc, $href, $cta]):
    ?>
      <div class="border border-[var(--color-border)] p-8 hover:border-[var(--color-gold-light)] transition-colors duration-300">
        <p class="text-2xl font-light text-[var(--color-gold)] mb-6 tracking-widest" style="font-family:var(--font-heading)"><?= $num ?></p>
        <h4 class="text-xs font-semibold tracking-widest uppercase mb-3"><?= $titre ?></h4>
        <p class="text-xs text-[var(--color-muted)] font-light leading-loose"><?= htmlspecialchars($desc) ?></p>
        <a href="<?= $href ?>" class="inline-block mt-6 text-xs tracking-widest text-[var(--color-gold)] hover:opacity-60 transition-opacity uppercase"><?= $cta ?> →</a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ── Célébrités ── -->
<section class="bg-[var(--color-warm)] py-20 px-4 md:px-10 xl:px-16">
  <div class="flex items-end justify-between mb-10">
    <div>
      <p class="eyebrow mb-2">Inspirations</p>
      <h3 class="text-2xl font-normal md:text-3xl leading-snug" style="font-family:var(--font-heading)">
        Leurs looks,<br><span class="text-[var(--color-gold)] italic">votre garde-robe</span>
      </h3>
    </div>
    <a href="/celebrities" class="hidden md:block text-xs tracking-widest text-[var(--color-faint)] uppercase hover:text-black transition-colors duration-300">Voir tout →</a>
  </div>

  <div class="grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
    <?php foreach ($celebrites as $celeb): ?>
      <article class="group cursor-pointer">
        <a href="/celebrities/<?= htmlspecialchars($celeb['slug']) ?>">
          <div class="media-card overflow-hidden aspect-[3/4] bg-[var(--color-cream-alt)]">
            <img src="/assets/img/<?= htmlspecialchars($celeb['photo']) ?>" alt="<?= htmlspecialchars($celeb['nom']) ?>" class="media-img w-full h-full object-cover object-top" />
          </div>
          <div class="pt-4 px-1">
            <h4 class="text-sm tracking-wide text-black"><?= htmlspecialchars($celeb['nom']) ?></h4>
            <p class="text-xs text-[var(--color-muted)] font-light mt-1"><?= htmlspecialchars($celeb['categorie'] ?? '') ?></p>
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-[var(--color-border)]">
              <span class="looks-badge"><?= (int)$celeb['nb_looks'] ?> looks</span>
              <span class="text-xs tracking-widest text-[var(--color-faint)] group-hover:text-black transition-colors duration-200">Voir →</span>
            </div>
          </div>
        </a>
      </article>
    <?php endforeach; ?>
    <?php if (empty($celebrites)): ?>
      <p class="text-[var(--color-muted)] text-sm col-span-4 text-center py-10">Aucune célébrité pour l'instant.</p>
    <?php endif; ?>
  </div>

  <div class="mt-8 text-center md:hidden">
    <a href="/celebrities" class="text-xs tracking-widest text-[var(--color-muted)] uppercase hover:text-black transition-colors duration-200">Voir toutes les célébrités →</a>
  </div>
</section>

<!-- ── Séries ── -->
<section class="bg-white py-20 px-4 md:px-10 xl:px-16">
  <div class="flex items-end justify-between mb-10">
    <div>
      <p class="eyebrow mb-2">Collections</p>
      <h3 class="text-2xl font-normal md:text-3xl leading-snug" style="font-family:var(--font-heading)">
        Les looks des séries,<br><span class="text-[var(--color-gold)] italic">votre prochaine tenue</span>
      </h3>
    </div>
    <a href="/series" class="hidden md:block text-xs tracking-widest text-[var(--color-faint)] uppercase hover:text-black transition-colors duration-300">Voir tout →</a>
  </div>

  <div class="grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
    <?php foreach ($series as $serie): ?>
      <article class="group cursor-pointer">
        <a href="/series/<?= htmlspecialchars($serie['slug']) ?>">
          <div class="relative media-card overflow-hidden aspect-[3/4] bg-[var(--color-cream-alt)]">
            <img src="/assets/img/<?= htmlspecialchars($serie['photo']) ?>" alt="<?= htmlspecialchars($serie['nom']) ?>" class="media-img w-full h-full object-cover object-center group-hover:saturate-80 group-hover:brightness-60 transition-all duration-400" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/15 to-transparent opacity-80 group-hover:opacity-95 transition-opacity duration-400"></div>
            <div class="absolute bottom-0 left-0 right-0 p-3 md:p-5 translate-y-2 group-hover:translate-y-0 transition-transform duration-400">
              <?php if (!empty($serie['style'])): ?>
                <div class="mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-400">
                  <span class="text-[8px] md:text-[10px] tracking-widest text-[var(--color-gold)] border border-[var(--color-gold)]/60 px-1.5 py-0.5 uppercase"><?= htmlspecialchars($serie['style']) ?></span>
                </div>
              <?php endif; ?>
              <h4 class="text-[11px] md:text-sm tracking-wide text-white font-light leading-snug"><?= htmlspecialchars($serie['nom']) ?></h4>
              <div class="flex items-center justify-between mt-2 pt-2 border-t border-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-400">
                <span class="looks-badge bg-transparent border-0 text-[var(--color-gold)]"><?= (int)$serie['nb_looks'] ?> looks</span>
                <span class="text-[8px] md:text-[10px] tracking-widest text-white/60 uppercase">Voir →</span>
              </div>
            </div>
          </div>
        </a>
      </article>
    <?php endforeach; ?>
    <?php if (empty($series)): ?>
      <p class="text-[var(--color-muted)] text-sm col-span-4 text-center py-10">Aucune série pour l'instant.</p>
    <?php endif; ?>
  </div>

  <div class="mt-8 text-center md:hidden">
    <a href="/series" class="text-xs tracking-widest text-[var(--color-muted)] uppercase hover:text-black transition-colors duration-200">Voir toutes les séries →</a>
  </div>
</section>

<!-- ── CTA final ── -->
<section class="bg-black py-28 px-4 text-center">
  <p class="eyebrow text-gray-600 mb-4">Prêt à commencer ?</p>
  <h3 class="text-white text-2xl font-normal md:text-4xl leading-snug tracking-tight mb-10" style="font-family:var(--font-heading)">Trouvez votre style dès maintenant</h3>
  <a href="/style-finder" class="btn-ghost-white">COMMENCER</a>
</section>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>