<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- ── Bannière hero ── -->
<div class="relative h-[60vh] min-h-[400px] overflow-hidden">
  <img src="/assets/img/magazine/cesar-banner.webp" alt="Cérémonie des Césars 2026" class="w-full h-full object-cover object-[center_20%]" />
  <div class="absolute inset-0 bg-gradient-to-t from-[rgba(10,10,8,.75)] via-[rgba(10,10,8,.15)] to-transparent flex items-end p-10 md:p-14">
    <div>
      <span class="inline-block text-[.62rem] tracking-[.2em] uppercase text-white/60 border border-white/30 px-3 py-1 mb-4">Édition spéciale · Césars 2026</span>
      <h1 class="text-white text-[clamp(1.6rem,4vw,3rem)] font-normal leading-snug max-w-[680px]" style="font-family:var(--font-heading)">
        La nuit qui a couronné le cinéma français
      </h1>
    </div>
  </div>
</div>

<!-- ── Wrapper ── -->
<div class="max-w-[1200px] mx-auto px-8">

  <!-- ── À la une ── -->
  <section class="py-14">
    <div class="mag-rule-top flex items-baseline justify-between">
      <div>
        <h2 class="text-[1.4rem] font-normal" style="font-family:var(--font-heading)">À la une</h2>
        <p class="text-[.70rem] tracking-[.12em] uppercase text-[var(--color-muted)]">Les sujets qui font l'actu</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-[1.6fr_1fr] border-t border-[var(--color-border)]">

      <!-- Article principal -->
      <article class="flex flex-col py-8 pr-0 md:pr-10 border-b md:border-b-0 md:border-r border-[var(--color-border)]">
        <div class="aspect-[4/3] overflow-hidden mb-5">
          <img src="/assets/img/magazine/benjamin-laverhne.webp" alt="Benjamin Laverhne aux Césars 2026" class="w-full h-full object-cover object-top transition-transform duration-500 hover:scale-[1.03]" />
        </div>
        <p class="eyebrow mb-2">Cinéma · 6 min</p>
        <h2 class="text-[1.6rem] font-normal leading-snug mb-3 flex-1" style="font-family:var(--font-heading); color:var(--color-text)">
          Benjamin Laverhne s'éclate avec Jim Carrey pour oublier que le monde brûle
        </h2>
        <p class="text-sm leading-[1.75] text-[var(--color-muted)] mb-5">
          La 51e cérémonie présidée par Camille Cottin couronne le drame délicat de Carine Tardieu. Jim Carrey reçoit le César d'honneur sous une ovation debout de la salle de l'Olympia.
        </p>
        <a href="#" class="article-link self-start text-[.62rem] tracking-[.14em] uppercase text-[var(--color-text)] no-underline border-b border-[var(--color-text)] pb-0.5 hover:text-[var(--color-gold)] hover:border-[var(--color-gold)] transition-colors">Lire la suite →</a>
      </article>

      <!-- Articles secondaires -->
      <div class="flex flex-col">
        <?php
          $secondaires = [
            ['magazine/laurent-lafitte.webp', 'Cinéma · 4 min', 'Laurent Lafitte, Meilleur Acteur : l\'about-face parfait d\'un comédien au sommet'],
            ['magazine/fashion-week.jpg',     'Mode · 5 min',   'Fashion Week Paris : les grandes maisons dictent l\'AH 2026'],
            ['magazine/frank-dubosc.webp',    'Style · 3 min',  'Street style : ce que les invités portent entre deux défilés'],
          ];
          foreach ($secondaires as [$img, $rubric, $titre]):
        ?>
          <article class="flex gap-4 py-6 px-0 md:pl-8 border-b border-[var(--color-border)] last:border-b-0 items-start group">
            <div class="w-[90px] flex-shrink-0 aspect-square overflow-hidden">
              <img src="/assets/img/<?= $img ?>" alt="<?= htmlspecialchars($titre) ?>" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-[1.06]" />
            </div>
            <div class="flex-1">
              <p class="eyebrow mb-1.5"><?= $rubric ?></p>
              <h3 class="text-[.94rem] font-normal leading-snug mb-3 text-[var(--color-text)]" style="font-family:var(--font-heading)"><?= htmlspecialchars($titre) ?></h3>
              <a href="#" class="text-[.62rem] tracking-[.14em] uppercase text-[var(--color-text)] no-underline border-b border-[var(--color-text)] pb-0.5 hover:text-[var(--color-gold)] hover:border-[var(--color-gold)] transition-colors">Lire →</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

    </div>
  </section>

  <!-- ── Newsletter ── -->
  <div class="bg-[var(--color-dark)] -mx-8 px-8 py-16 text-center mb-0">
    <p class="eyebrow text-[var(--color-gold-light)] mb-4">La lettre du vendredi</p>
    <h2 class="text-[clamp(1.5rem,3vw,2.4rem)] font-normal text-white leading-snug mb-3" style="font-family:var(--font-heading)">
      L'essentiel de la semaine,<br>directement chez vous.
    </h2>
    <p class="text-sm text-white/50 max-w-sm mx-auto mb-8 leading-[1.7]">Palmarès, tendances, portraits — notre sélection éditoriale chaque vendredi matin.</p>
    <div class="flex max-w-[460px] mx-auto">
      <input type="email" placeholder="Votre adresse email" class="flex-1 bg-white/7 border border-white/15 border-r-0 px-5 py-3 text-sm text-white placeholder:text-white/35 outline-none focus:border-[var(--color-gold-light)] transition-colors" />
      <button class="bg-[var(--color-gold)] border border-[var(--color-gold)] text-white px-5 text-[.62rem] tracking-[.16em] uppercase cursor-pointer hover:bg-[var(--color-gold-hover)] transition-colors whitespace-nowrap">S'abonner</button>
    </div>
    <p class="text-[.70rem] text-white/30 mt-3">Aucun spam. Désabonnement en un clic.</p>
  </div>

  <!-- ── Populaire ── -->
  <section class="py-14">
    <div class="mag-rule-top flex items-baseline justify-between">
      <div>
        <h2 class="text-[1.4rem] font-normal" style="font-family:var(--font-heading)">Populaire</h2>
        <p class="text-[.70rem] tracking-[.12em] uppercase text-[var(--color-muted)]">Les plus lus cette semaine</p>
      </div>
      <a href="#" class="text-[.62rem] tracking-[.16em] uppercase text-[var(--color-muted)] no-underline border-b border-[var(--color-border)] pb-0.5 hover:text-[var(--color-gold)] hover:border-[var(--color-gold)] transition-colors">Tout voir →</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3">
      <?php
        $articles = [
          ['magazine/godard.webp',              'Cinéma · 5 min', 'Nouvelle Vague de Linklater : 4 Césars techniques pour un hommage à Godard',     'Recordman de nominations avec 12 citations, le film repart avec les prix de réalisation, montage, costumes et photographie.'],
          ['magazine/benjamin-laverhne.webp',   'Cinéma · 4 min', 'Jim Carrey, César d\'honneur : retour sur une carrière hors du commun',           'De The Mask à Eternal Sunshine, l\'acteur américain a redéfini les frontières entre comédie et drame avec une intensité rare.'],
          ['magazine/frank-dubosc.webp',        'Culture · 3 min','Camille Cottin, maîtresse de cérémonie irréprochable au fil du rasoir',           'Humour acéré, silences calculés — elle a tenu la salle de l\'Olympia avec une aisance désarmante du début à la fin.'],
        ];
        foreach ($articles as $i => [$img, $rubric, $titre, $excerpt]):
      ?>
        <article class="flex flex-col py-8 group <?= $i===0 ? 'md:pr-8 md:border-r border-[var(--color-border)]' : ($i===1 ? 'md:px-8 md:border-r border-[var(--color-border)]' : 'md:pl-8') ?>">
          <div class="aspect-[3/2] overflow-hidden mb-4">
            <img src="/assets/img/<?= $img ?>" alt="<?= htmlspecialchars($titre) ?>" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-[1.04]" />
          </div>
          <p class="eyebrow mb-2"><?= $rubric ?></p>
          <h3 class="text-[1.1rem] font-normal leading-snug mb-3 flex-1 text-[var(--color-text)]" style="font-family:var(--font-heading)"><?= htmlspecialchars($titre) ?></h3>
          <p class="text-[.81rem] leading-[1.7] text-[var(--color-muted)] mb-4"><?= htmlspecialchars($excerpt) ?></p>
          <a href="#" class="self-start text-[.62rem] tracking-[.14em] uppercase text-[var(--color-text)] no-underline border-b border-[var(--color-text)] pb-0.5 hover:text-[var(--color-gold)] hover:border-[var(--color-gold)] transition-colors">Lire la suite →</a>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ── Tendances ── -->
  <section class="py-14 -mx-8 px-8 bg-[var(--color-warm)]">
    <div class="mag-rule-top flex items-baseline justify-between">
      <div>
        <h2 class="text-[1.4rem] font-normal" style="font-family:var(--font-heading)">Tendances</h2>
        <p class="text-[.70rem] tracking-[.12em] uppercase text-[var(--color-muted)]">Mode, beauté &amp; art de vivre</p>
      </div>
      <a href="#" class="text-[.62rem] tracking-[.16em] uppercase text-[var(--color-muted)] no-underline border-b border-[var(--color-border)] pb-0.5 hover:text-[var(--color-gold)] hover:border-[var(--color-gold)] transition-colors">Tout voir →</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3">
      <?php
        $tendances = [
          ['magazine/fashion-week.jpg',      'Mode · 5 min', 'Les silhouettes qui domineront l\'automne-hiver 2026',                               'Des épaules structurées chez Saint Laurent, des volumes extrêmes chez Balenciaga — le tailoring s\'impose comme la pièce maîtresse de la saison.'],
          ['magazine/laurent-lafitte.webp',  'Style · 4 min','Smoking revisité, velours et boucles d\'oreilles : le style homme s\'affranchit',    'Sur le tapis rouge, les hommes jouent la fluidité et les matières précieuses. Un glissement discret mais net vers une élégance libérée.'],
          ['magazine/frank-dubosc.webp',     'Style · 3 min','Street style : ce que les invités portent entre deux défilés',                       'Manteaux surdimensionnés, sneakers rares et sacs sculptés — le vrai spectacle se passe parfois en dehors des podiums.'],
        ];
        foreach ($tendances as $i => [$img, $rubric, $titre, $excerpt]):
      ?>
        <article class="flex flex-col py-8 group <?= $i===0 ? 'md:pr-8 md:border-r border-[var(--color-border)]' : ($i===1 ? 'md:px-8 md:border-r border-[var(--color-border)]' : 'md:pl-8') ?>">
          <div class="aspect-[3/2] overflow-hidden mb-4">
            <img src="/assets/img/<?= $img ?>" alt="<?= htmlspecialchars($titre) ?>" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-[1.04]" />
          </div>
          <p class="eyebrow mb-2"><?= $rubric ?></p>
          <h3 class="text-[1.1rem] font-normal leading-snug mb-3 flex-1 text-[var(--color-text)]" style="font-family:var(--font-heading)"><?= htmlspecialchars($titre) ?></h3>
          <p class="text-[.81rem] leading-[1.7] text-[var(--color-muted)] mb-4"><?= htmlspecialchars($excerpt) ?></p>
          <a href="#" class="self-start text-[.62rem] tracking-[.14em] uppercase text-[var(--color-text)] no-underline border-b border-[var(--color-text)] pb-0.5 hover:text-[var(--color-gold)] hover:border-[var(--color-gold)] transition-colors">Lire la suite →</a>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>