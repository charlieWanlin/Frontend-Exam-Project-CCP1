<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- ── Hero ── -->
<section class="pt-16 md:pt-24 pb-8 md:pb-14 px-4 text-center">
  <p class="eyebrow anim-fu-1 mb-3">IA Search</p>
  <h1 class="anim-fu-2 italic font-light text-3xl md:text-6xl text-[var(--color-text)] mb-3" style="font-family:var(--font-heading)">Identifiez le look</h1>
  <p class="anim-fu-3 text-[.72rem] text-[var(--color-muted)] tracking-wide max-w-xs md:max-w-sm mx-auto leading-relaxed">
    Uploadez une photo et notre IA trouvera les vêtements pour vous
  </p>
</section>

<!-- ── Drop zone ── -->
<section class="px-4 pb-12 md:pb-16 flex justify-center">
  <div class="w-full max-w-xl">

    <div id="drop-zone" class="bg-white border border-dashed border-[var(--color-border)] hover:border-[var(--color-muted)] p-8 md:p-16 flex flex-col items-center justify-center gap-4 cursor-pointer transition-all duration-300 group">
      <!-- Preview -->
      <div id="preview-wrapper" class="hidden w-full flex-col items-center gap-4">
        <img id="preview-img" src="" alt="preview" class="max-h-52 md:max-h-72 object-contain" />
        <button id="remove-btn" class="text-[.72rem] tracking-widest uppercase cursor-pointer text-[var(--color-muted)] hover:text-[var(--color-text)] transition-colors">Supprimer</button>
      </div>
      <!-- Contenu par défaut -->
      <div id="drop-content" class="flex flex-col items-center gap-4">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-[var(--color-faint)] group-hover:text-[var(--color-muted)] transition-colors">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
        </svg>
        <div class="text-center">
          <p class="text-sm text-[var(--color-muted)] font-light">Glissez votre photo ici</p>
          <p class="text-xs text-[var(--color-faint)] mt-1 hidden md:block">ou cliquez pour parcourir</p>
        </div>
        <button id="choose-btn" class="btn-dark px-6 py-2.5">Choisir une photo</button>
        <p class="text-[.70rem] text-[var(--color-faint)] tracking-wide hidden md:block">PNG, JPG, WEBP · Max 10 Mo</p>
      </div>
    </div>

    <input type="file" id="file-input" accept="image/*" class="hidden" />

    <div id="analyze-btn-wrapper" class="hidden mt-3">
      <button id="analyze-btn" class="btn-dark w-full justify-center py-4">Analyser le look →</button>
    </div>
  </div>
</section>

<!-- ── Exemples ── -->
<section class="px-4 pb-16">
  <p class="eyebrow text-center mb-6">Quelques exemples</p>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-3 max-w-3xl mx-auto">
    <?php
      $examples = [
        ['billie-elish-outfit.jpg',   'Billie Eilish'],
        ['peaky-blinders-outfit.jpg', 'Peaky Blinders'],
        ['jenna-ortega-outfit.jpg',   'Jenna Ortega'],
        ['emily-in-paris-outfit.jpg', 'Emily in Paris'],
      ];
      foreach ($examples as [$file, $alt]):
    ?>
      <img src="/assets/img/outfits/<?= $file ?>" alt="<?= $alt ?>" class="w-full aspect-[3/4] object-cover object-top cursor-pointer hover:opacity-80 transition-opacity duration-300" />
    <?php endforeach; ?>
  </div>
</section>

<!-- ── Comment ça marche ── -->
<section class="bg-[var(--color-warm)] px-4 py-16 md:py-20">
  <div class="max-w-5xl mx-auto">
    <p class="eyebrow text-center mb-3">Notre technologie</p>
    <h2 class="text-2xl md:text-4xl font-light text-center mb-12" style="font-family:var(--font-heading)">Comment ça marche</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
      <?php
        $steps = [
          ['01','Analyse de l\'image',    'Notre IA détecte automatiquement chaque vêtement et accessoire présent sur la photo.'],
          ['02','Identification précise', 'Reconnaissance de la marque, du modèle et des caractéristiques exactes du vêtement.'],
          ['03','Résultats instantanés',  'Trouvez l\'article et toutes les alternatives disponibles chez vos marques préférées.'],
        ];
        foreach ($steps as [$num, $titre, $desc]):
      ?>
        <div class="bg-white p-6 md:p-8 border border-[var(--color-border)]">
          <p class="text-[.70rem] tracking-widest text-[var(--color-faint)] mb-5"><?= $num ?></p>
          <h3 class="text-xs font-medium tracking-wide mb-2"><?= htmlspecialchars($titre) ?></h3>
          <p class="text-[.72rem] text-[var(--color-muted)] font-light leading-relaxed"><?= htmlspecialchars($desc) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── Atouts ── -->
<section class="px-4 py-16 md:py-20">
  <div class="max-w-5xl mx-auto">
    <p class="eyebrow text-center mb-12">Nos atouts</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-10">
      <?php
        $atouts = [
          ['Multi-détection', 'Plusieurs vêtements sur une même photo'],
          ['Haute précision', '95% d\'articles correctement identifiés'],
          ['Base de données', 'Plus d\'un million d\'articles référencés'],
          ['Pour tous',       'Simple et accessible à tous'],
        ];
        foreach ($atouts as [$titre, $desc]):
      ?>
        <div class="flex flex-col items-center text-center gap-3">
          <div class="w-9 h-9 rounded-full border border-[var(--color-border)] flex items-center justify-center">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 3"/></svg>
          </div>
          <div>
            <p class="text-xs font-medium tracking-wide mb-1"><?= htmlspecialchars($titre) ?></p>
            <p class="text-[.70rem] text-[var(--color-muted)] font-light leading-relaxed"><?= htmlspecialchars($desc) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── CTA final ── -->
<section class="bg-[var(--color-dark)] text-white px-4 py-16 md:py-24 text-center">
  <p class="eyebrow text-[var(--color-muted)] mb-5">Essayez maintenant</p>
  <h2 class="italic font-light text-2xl md:text-5xl mb-3" style="font-family:var(--font-heading)">Vous avez un look en tête ?</h2>
  <p class="text-xs text-white/40 tracking-wide mb-10 max-w-xs md:max-w-sm mx-auto leading-relaxed">Importez n'importe quelle photo — célébrité, série, magazine — notre IA s'occupe du reste</p>
  <div id="cta-scroll" class="border border-dashed border-white/20 bg-white/5 max-w-xs md:max-w-md mx-auto p-10 flex flex-col items-center gap-4 cursor-pointer hover:border-white/40 transition-colors duration-300">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-white/30"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
    <p class="text-xs text-white/40">Glissez votre photo ici</p>
    <button id="cta-btn" class="bg-white text-[var(--color-dark)] text-xs tracking-[.25em] uppercase px-8 py-3 hover:bg-[var(--color-cream)] transition-colors cursor-pointer">Upload une photo →</button>
  </div>
</section>

<script src="/js/style-finder.js" defer></script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>