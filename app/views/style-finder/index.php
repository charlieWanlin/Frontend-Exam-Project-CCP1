<?php require_once __DIR__ . '/../partials/header.php'; ?>

<style>
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }ma
  .animate-fu-1 { animation: fadeUp .9s .15s ease both; }
  .animate-fu-2 { animation: fadeUp .9s .4s  ease both; }
  .animate-fu-3 { animation: fadeUp .8s .7s  ease both; }
</style>


<!-- HERO -->
<section class="pt-14 md:pt-24 pb-8 md:pb-16 px-4 text-center">
  <p class="animate-fu-1 text-[9px] md:text-[10px] tracking-[0.3em] uppercase text-gray-400 mb-3 md:mb-4 font-body">
    IA Search
  </p>
  <h1 class="animate-fu-2 font-heading text-3xl md:text-6xl font-light italic text-gray-900 mb-3 md:mb-4">
    Identifiez le look
  </h1>
  <p class="animate-fu-3 text-[11px] md:text-xs text-gray-400 tracking-wide max-w-xs md:max-w-sm mx-auto leading-relaxed font-body">
    Uploadez une photo et notre IA trouvera les vêtements pour vous
  </p>
</section>


<!-- DROP ZONE -->
<section class="px-4 pb-10 md:pb-16 flex justify-center">
  <div class="w-full max-w-xl">
    <div
      id="drop-zone"
      class="bg-white border border-dashed border-gray-300 hover:border-gray-500 rounded-sm p-8 md:p-16 flex flex-col items-center justify-center gap-4 md:gap-5 cursor-pointer transition-all duration-300 group"
    >
      <!-- Preview image -->
      <div id="preview-wrapper" class="hidden w-full flex-col items-center gap-4">
        <img id="preview-img" src="" alt="preview" class="max-h-52 md:max-h-72 object-contain rounded-sm" />
        <button id="remove-btn" class="text-[9px] md:text-[10px] tracking-widest uppercase cursor-pointer text-gray-400 hover:text-black transition-colors font-body">
          Supprimer
        </button>
      </div>

      <!-- Contenu par défaut -->
      <div id="drop-content" class="flex flex-col items-center gap-3 md:gap-5">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-gray-400 group-hover:text-gray-600 transition-colors md:w-6 md:h-6">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
          <polyline points="17 8 12 3 7 8" />
          <line x1="12" y1="3" x2="12" y2="15" />
        </svg>
        <div class="text-center">
          <p class="text-xs md:text-sm text-gray-600 font-light font-body">Glissez votre photo ici</p>
          <p class="text-[10px] md:text-xs text-gray-400 mt-1 hidden md:block font-body">ou cliquez pour parcourir</p>
        </div>
        <button id="choose-btn" class="bg-gray-900 text-white text-[9px] md:text-[10px] tracking-[0.2em] uppercase px-5 md:px-8 py-2.5 md:py-3 cursor-pointer hover:bg-gray-700 transition-colors duration-200 font-body">
          Choisir une photo
        </button>
        <p class="text-[10px] text-gray-300 tracking-wide hidden md:block font-body">PNG, JPG, WEBP · Max 10 Mo</p>
      </div>
    </div>

    <input type="file" id="file-input" accept="image/*" class="hidden" />

    <div id="analyze-btn-wrapper" class="hidden mt-3 md:mt-4">
      <button id="analyze-btn" class="w-full bg-gray-900 text-white text-[9px] md:text-[10px] tracking-[0.25em] cursor-pointer uppercase py-3.5 md:py-4 hover:bg-gray-700 transition-colors duration-200 font-body">
        Analyser le look →
      </button>
    </div>
  </div>
</section>


<!-- EXEMPLES -->
<section class="px-4 pb-14 md:pb-20">
  <p class="text-[9px] md:text-[10px] tracking-[0.3em] uppercase text-gray-400 text-center mb-5 md:mb-8 font-body">
    Quelques exemples
  </p>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-3 max-w-3xl mx-auto">
    <img src="/assets/img/outfits/billie-elish-outfit.jpg"      alt="Billie Eilish outfit"    class="w-full aspect-[3/4] object-cover object-top cursor-pointer hover:opacity-80 transition-opacity duration-300" />
    <img src="/assets/img/outfits/peaky-blinders-outfit.jpg"    alt="Peaky Blinders outfit"   class="w-full aspect-[3/4] object-cover object-top cursor-pointer hover:opacity-80 transition-opacity duration-300" />
    <img src="/assets/img/outfits/jenna-ortega-outfit.jpg"      alt="Jenna Ortega outfit"     class="w-full aspect-[3/4] object-cover object-top cursor-pointer hover:opacity-80 transition-opacity duration-300" />
    <img src="/assets/img/outfits/emily-in-paris-outfit.jpg"    alt="Emily in Paris outfit"   class="w-full aspect-[3/4] object-cover object-top cursor-pointer hover:opacity-80 transition-opacity duration-300" />
  </div>
</section>


<!-- COMMENT ÇA MARCHE -->
<section class="bg-[#f9f7f4] px-4 py-14 md:py-20">
  <div class="max-w-5xl mx-auto">
    <p class="text-[9px] md:text-[10px] tracking-[0.3em] uppercase text-gray-400 text-center mb-3 md:mb-4 font-body">
      Notre technologie
    </p>
    <h2 class="font-heading text-2xl md:text-4xl font-light text-center mb-8 md:mb-16">
      Comment ça marche
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-6">
      <?php foreach ([
        ['01', 'Analyse de l\'image',    'Notre IA détecte automatiquement chaque vêtement et accessoire présent sur la photo.'],
        ['02', 'Identification précise', 'Reconnaissance de la marque, du modèle et des caractéristiques exactes du vêtement.'],
        ['03', 'Résultats instantanés',  'Trouvez l\'article et toutes les alternatives disponibles chez vos marques préférées.'],
      ] as [$num, $titre, $desc]): ?>
        <div class="bg-white p-5 md:p-8 border border-gray-100">
          <p class="text-[10px] tracking-widest text-gray-300 mb-3 md:mb-6 font-body"><?= $num ?></p>
          <h3 class="text-xs md:text-sm font-medium tracking-wide mb-2 md:mb-3 font-body"><?= htmlspecialchars($titre) ?></h3>
          <p class="text-[10px] md:text-xs text-gray-400 font-light leading-relaxed font-body"><?= htmlspecialchars($desc) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- NOS ATOUTS -->
<section class="px-4 py-14 md:py-20">
  <div class="max-w-5xl mx-auto">
    <p class="text-[9px] md:text-[10px] tracking-[0.3em] uppercase text-gray-400 text-center mb-10 md:mb-16 font-body">
      Nos atouts
    </p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-10">
      <?php foreach ([
        ['Multi-détection',  'Plusieurs vêtements sur une même photo',         'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4 M17 8 12 3 7 8 M12 3v12'],
        ['Haute précision',  '95% d\'articles correctement identifiés',         'M20 6 9 17 4 12'],
        ['Base de données',  'Plus d\'un million d\'articles référencés',        'M2 3h20v14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3z M8 21h8 M12 17v4'],
        ['Pour tous',        'Simple et accessible à tous',                     'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8z'],
      ] as [$titre, $desc, $path]): ?>
        <div class="flex flex-col items-center text-center gap-3">
          <div class="w-9 h-9 border border-gray-200 rounded-full flex items-center justify-center shrink-0">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <?php foreach (explode(' M', $path) as $i => $p): ?>
                <path d="<?= ($i === 0 ? '' : 'M') . htmlspecialchars($p) ?>"/>
              <?php endforeach; ?>
            </svg>
          </div>
          <div>
            <p class="text-[11px] md:text-xs font-medium tracking-wide mb-1 font-body"><?= htmlspecialchars($titre) ?></p>
            <p class="text-[9px] md:text-[10px] text-gray-400 font-light leading-relaxed font-body"><?= htmlspecialchars($desc) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- CTA FINAL -->
<section class="bg-gray-950 text-white px-4 py-14 md:py-24 text-center">
  <p class="text-[9px] md:text-[10px] tracking-[0.3em] uppercase text-gray-500 mb-5 md:mb-6 font-body">
    Essayez maintenant
  </p>
  <h2 class="font-heading text-2xl md:text-5xl font-light italic mb-3 md:mb-4">
    Vous avez un look en tête ?
  </h2>
  <p class="text-[11px] md:text-xs text-gray-400 tracking-wide mb-8 md:mb-12 max-w-xs md:max-w-sm mx-auto leading-relaxed font-body">
    Importez n'importe quelle photo — célébrité, série, magazine — notre IA s'occupe du reste
  </p>
  <div id="cta-scroll" class="border border-dashed border-gray-700 bg-gray-900 max-w-xs md:max-w-md mx-auto p-8 md:p-12 flex flex-col items-center gap-3 md:gap-5 cursor-pointer hover:border-gray-500 transition-colors duration-300">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-gray-500">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
      <polyline points="17 8 12 3 7 8" />
      <line x1="12" y1="3" x2="12" y2="15" />
    </svg>
    <p class="text-[10px] md:text-xs text-gray-400 font-body">Glissez votre photo ici</p>
    <button id="cta-btn" class="bg-white text-gray-900 text-[9px] md:text-[10px] tracking-[0.25em] cursor-pointer uppercase px-5 md:px-8 py-2.5 md:py-3 hover:bg-gray-200 transition-colors duration-200 font-body">
      Upload une photo →
    </button>
  </div>
</section>


<script src="/js/style-finder.js" defer></script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>