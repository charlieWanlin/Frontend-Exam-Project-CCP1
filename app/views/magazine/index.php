<?php require_once __DIR__ . '/../partials/header.php'; ?>

<style>
  :root {
    --ink: #1a1a18;
    --ink-2: #4a4a45;
    --ink-3: #9a9a94;
    --cream: #f7f5f0;
    --warm: #f0ede6;
    --gold: #b8860b;
    --gold-light: #d4a017;
    --rule: #e0ddd6;
    --white: #ffffff;
    --serif: 'Georgia', 'Times New Roman', serif;
    --sans: system-ui, -apple-system, sans-serif;
  }

  /* Reset scoped à .mag-body uniquement — ne touche pas le <body> ni la navbar */
  .mag-body *,
  .mag-body *::before,
  .mag-body *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  .mag-body {
    background: var(--cream);
    color: var(--ink);
    font-family: var(--sans);
  }

  /* ── BANNER ── */
  .banner {
    position: relative;
    height: 60vh;
    min-height: 400px;
    overflow: hidden;
  }
  .banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 20%;
    display: block;
  }
  .banner-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,10,8,.75) 0%, rgba(10,10,8,.15) 55%, transparent 100%);
    display: flex;
    align-items: flex-end;
    padding: 2.5rem 3rem;
  }
  .banner-label {
    display: inline-block;
    font-size: 10px;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: rgba(255,255,255,.6);
    border: 1px solid rgba(255,255,255,.3);
    padding: 5px 12px;
    margin-bottom: 14px;
  }
  .banner-title {
    font-family: var(--serif);
    font-size: clamp(26px, 4vw, 48px);
    font-weight: 400;
    line-height: 1.2;
    color: #fff;
    max-width: 680px;
    letter-spacing: -.01em;
  }

  /* ── LAYOUT WRAPPER ── */
  .mag-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
  }

  /* ── SECTION HEADER ── */
  .section-header {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    border-top: 2px solid var(--ink);
    border-bottom: 1px solid var(--rule);
    padding: 12px 0 14px;
    margin-bottom: 2.5rem;
  }
  .section-header h2 {
    font-family: var(--serif);
    font-size: 22px;
    font-weight: 400;
    letter-spacing: -.01em;
  }
  .section-header p {
    font-size: 11px;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--ink-3);
  }
  .see-all {
    font-size: 10px;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: var(--ink-2);
    text-decoration: none;
    border-bottom: 1px solid var(--rule);
    padding-bottom: 2px;
    transition: color .2s, border-color .2s;
  }
  .see-all:hover { color: var(--gold); border-color: var(--gold); }

  /* ── SECTION ── */
  .mag-section {
    padding: 3.5rem 0;
  }
  .mag-section + .mag-section {
    border-top: 1px solid var(--rule);
  }

  /* ── CARD GRID ── */
  .article-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
  }
  @media (max-width: 900px) {
    .article-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 580px) {
    .article-grid { grid-template-columns: 1fr; }
  }

  .article-card {
    display: flex;
    flex-direction: column;
    padding: 0 2rem 2.5rem 0;
    border-right: 1px solid var(--rule);
  }
  .article-card:last-child,
  .article-card:nth-child(3n) {
    border-right: none;
    padding-right: 0;
  }
  .article-card:not(:first-child) {
    padding-left: 2rem;
    border-right: 1px solid var(--rule);
  }
  .article-card:nth-child(3n) {
    border-right: none;
  }

  .article-thumb {
    aspect-ratio: 3 / 2;
    overflow: hidden;
    margin-bottom: 1rem;
  }
  .article-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
    display: block;
    transition: transform .6s ease;
  }
  .article-card:hover .article-thumb img {
    transform: scale(1.04);
  }

  .article-rubric {
    font-size: 9px;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 8px;
  }

  .article-title {
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 400;
    line-height: 1.4;
    letter-spacing: -.01em;
    color: var(--ink);
    margin-bottom: 10px;
    flex: 1;
  }

  .article-excerpt {
    font-size: 13px;
    line-height: 1.7;
    color: var(--ink-2);
    margin-bottom: 1.25rem;
  }

  .article-link {
    font-size: 10px;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--ink);
    text-decoration: none;
    border-bottom: 1px solid var(--ink);
    padding-bottom: 2px;
    align-self: flex-start;
    transition: color .2s, border-color .2s;
  }
  .article-link:hover { color: var(--gold); border-color: var(--gold); }

  /* ── FEATURED ── */
  .featured-grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 0;
    border-top: 1px solid var(--rule);
  }
  @media (max-width: 800px) {
    .featured-grid { grid-template-columns: 1fr; }
  }

  .featured-main {
    padding: 2rem 2.5rem 2rem 0;
    border-right: 1px solid var(--rule);
    display: flex;
    flex-direction: column;
  }
  .featured-thumb {
    aspect-ratio: 4 / 3;
    overflow: hidden;
    margin-bottom: 1.25rem;
  }
  .featured-thumb img {
    width: 100%; height: 100%;
    object-fit: cover; object-position: top;
    display: block;
    transition: transform .6s ease;
  }
  .featured-main:hover .featured-thumb img { transform: scale(1.03); }

  .featured-title {
    font-family: var(--serif);
    font-size: 26px;
    font-weight: 400;
    line-height: 1.3;
    letter-spacing: -.015em;
    color: var(--ink);
    margin-bottom: 12px;
    flex: 1;
  }
  .featured-excerpt {
    font-size: 14px;
    line-height: 1.75;
    color: var(--ink-2);
    margin-bottom: 1.5rem;
  }

  .featured-side {
    display: flex;
    flex-direction: column;
  }
  .side-article {
    display: flex;
    gap: 1rem;
    padding: 1.5rem 0 1.5rem 2.5rem;
    border-bottom: 1px solid var(--rule);
    align-items: flex-start;
  }
  .side-article:last-child { border-bottom: none; }

  .side-thumb {
    width: 90px;
    flex-shrink: 0;
    aspect-ratio: 1;
    overflow: hidden;
  }
  .side-thumb img {
    width: 100%; height: 100%;
    object-fit: cover; object-position: top;
    display: block;
    transition: transform .6s ease;
  }
  .side-article:hover .side-thumb img { transform: scale(1.06); }

  .side-content { flex: 1; }
  .side-title {
    font-family: var(--serif);
    font-size: 15px;
    font-weight: 400;
    line-height: 1.4;
    color: var(--ink);
    margin-bottom: 8px;
  }

  /* ── NEWSLETTER ── */
  .newsletter {
    background: var(--ink);
    padding: 4rem 2rem;
    text-align: center;
  }
  .newsletter-eyebrow {
    font-size: 10px;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--gold-light);
    margin-bottom: 1rem;
  }
  .newsletter-title {
    font-family: var(--serif);
    font-size: clamp(24px, 3vw, 38px);
    font-weight: 400;
    color: #fff;
    line-height: 1.25;
    letter-spacing: -.01em;
    margin-bottom: .75rem;
  }
  .newsletter-sub {
    font-size: 14px;
    color: rgba(255,255,255,.5);
    max-width: 420px;
    margin: 0 auto 2rem;
    line-height: 1.7;
  }
  .newsletter-form {
    display: flex;
    max-width: 460px;
    margin: 0 auto;
  }
  .newsletter-form input {
    flex: 1;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.15);
    border-right: none;
    padding: 13px 18px;
    font-size: 14px;
    color: #fff;
    outline: none;
    font-family: var(--sans);
    transition: border-color .2s;
  }
  .newsletter-form input::placeholder { color: rgba(255,255,255,.35); }
  .newsletter-form input:focus { border-color: var(--gold-light); }
  .newsletter-form button {
    background: var(--gold);
    border: 1px solid var(--gold);
    color: #fff;
    padding: 13px 22px;
    font-size: 10px;
    letter-spacing: .16em;
    text-transform: uppercase;
    cursor: pointer;
    white-space: nowrap;
    font-family: var(--sans);
    transition: background .2s;
  }
  .newsletter-form button:hover { background: var(--gold-light); }
  .newsletter-note {
    font-size: 11px;
    color: rgba(255,255,255,.3);
    margin-top: .75rem;
  }
</style>

<div class="mag-body">

  <!-- ══ BANNER ══ -->
  <div class="banner">
    <img src="/assets/img/magazine/cesar-banner.webp" alt="Cérémonie des Césars 2026" />
    <div class="banner-overlay">
      <div>
        <span class="banner-label">Édition spéciale · Césars 2026</span>
        <h1 class="banner-title">La nuit qui a couronné<br>le cinéma français</h1>
      </div>
    </div>
  </div>

  <!-- ══ À LA UNE ══ -->
  <section class="mag-section">
    <div class="mag-wrap">
      <div class="section-header">
        <div>
          <h2>À la une</h2>
          <p>Les sujets qui font l'actu</p>
        </div>
      </div>

      <div class="featured-grid">

        <article class="featured-main">
          <div class="featured-thumb">
            <img src="/assets/img/magazine/benjamin-laverhne.webp" alt="Benjamin Laverhne aux Césars 2026" />
          </div>
          <span class="article-rubric">Cinéma · 6 min</span>
          <h2 class="featured-title">
            Benjamin Laverhne s'éclate avec Jim Carrey pour oublier que le monde brûle
          </h2>
          <p class="featured-excerpt">
            La 51e cérémonie présidée par Camille Cottin couronne le drame délicat de Carine Tardieu. Jim Carrey reçoit le César d'honneur sous une ovation debout de la salle de l'Olympia.
          </p>
          <a href="#" class="article-link">Lire la suite →</a>
        </article>

        <div class="featured-side">

          <article class="side-article">
            <div class="side-thumb">
              <img src="/assets/img/magazine/laurent-lafitte.webp" alt="Laurent Lafitte" />
            </div>
            <div class="side-content">
              <span class="article-rubric">Cinéma · 4 min</span>
              <h3 class="side-title">Laurent Lafitte, Meilleur Acteur : l'about-face parfait d'un comédien au sommet</h3>
              <a href="#" class="article-link">Lire →</a>
            </div>
          </article>

          <article class="side-article">
            <div class="side-thumb">
              <img src="/assets/img/magazine/fashion-week.jpg" alt="Fashion Week Paris" />
            </div>
            <div class="side-content">
              <span class="article-rubric">Mode · 5 min</span>
              <h3 class="side-title">Fashion Week Paris : les grandes maisons dictent l'AH 2026</h3>
              <a href="#" class="article-link">Lire →</a>
            </div>
          </article>

          <article class="side-article">
            <div class="side-thumb">
              <img src="/assets/img/magazine/frank-dubosc.webp" alt="Street style" />
            </div>
            <div class="side-content">
              <span class="article-rubric">Style · 3 min</span>
              <h3 class="side-title">Street style : ce que les invités portent entre deux défilés</h3>
              <a href="#" class="article-link">Lire →</a>
            </div>
          </article>

        </div>
      </div>
    </div>
  </section>

  <!-- ══ NEWSLETTER ══ -->
  <div class="newsletter">
    <p class="newsletter-eyebrow">La lettre du vendredi</p>
    <h2 class="newsletter-title">L'essentiel de la semaine,<br>directement chez vous.</h2>
    <p class="newsletter-sub">Palmarès, tendances, portraits — notre sélection éditoriale chaque vendredi matin.</p>
    <div class="newsletter-form">
      <input type="email" placeholder="Votre adresse email" />
      <button type="button">S'abonner</button>
    </div>
    <p class="newsletter-note">Aucun spam. Désabonnement en un clic.</p>
  </div>

  <!-- ══ POPULAIRE ══ -->
  <section class="mag-section">
    <div class="mag-wrap">
      <div class="section-header">
        <div>
          <h2>Populaire</h2>
          <p>Les plus lus cette semaine</p>
        </div>
        <a href="#" class="see-all">Tout voir →</a>
      </div>

      <div class="article-grid">

        <article class="article-card">
          <div class="article-thumb">
            <img src="/assets/img/magazine/godard.webp" alt="Nouvelle Vague de Linklater" />
          </div>
          <span class="article-rubric">Cinéma · 5 min</span>
          <h3 class="article-title">Nouvelle Vague de Linklater : 4 Césars techniques pour un hommage à Godard</h3>
          <p class="article-excerpt">Recordman de nominations avec 12 citations, le film repart avec les prix de réalisation, montage, costumes et photographie.</p>
          <a href="#" class="article-link">Lire la suite →</a>
        </article>

        <article class="article-card">
          <div class="article-thumb">
            <img src="/assets/img/magazine/benjamin-laverhne.webp" alt="César d'honneur Jim Carrey" />
          </div>
          <span class="article-rubric">Cinéma · 4 min</span>
          <h3 class="article-title">Jim Carrey, César d'honneur : retour sur une carrière hors du commun</h3>
          <p class="article-excerpt">De The Mask à Eternal Sunshine, l'acteur américain a redéfini les frontières entre comédie et drame avec une intensité rare.</p>
          <a href="#" class="article-link">Lire la suite →</a>
        </article>

        <article class="article-card">
          <div class="article-thumb">
            <img src="/assets/img/magazine/frank-dubosc.webp" alt="Camille Cottin maîtresse de cérémonie" />
          </div>
          <span class="article-rubric">Culture · 3 min</span>
          <h3 class="article-title">Camille Cottin, maîtresse de cérémonie irréprochable au fil du rasoir</h3>
          <p class="article-excerpt">Humour acéré, silences calculés — elle a tenu la salle de l'Olympia avec une aisance désarmante du début à la fin.</p>
          <a href="#" class="article-link">Lire la suite →</a>
        </article>

      </div>
    </div>
  </section>

  <!-- ══ TENDANCES ══ -->
  <section class="mag-section" style="background: var(--warm);">
    <div class="mag-wrap">
      <div class="section-header">
        <div>
          <h2>Tendances</h2>
          <p>Mode, beauté &amp; art de vivre</p>
        </div>
        <a href="#" class="see-all">Tout voir →</a>
      </div>

      <div class="article-grid">

        <article class="article-card">
          <div class="article-thumb">
            <img src="/assets/img/magazine/fashion-week.jpg" alt="Fashion Week AH 2026" />
          </div>
          <span class="article-rubric">Mode · 5 min</span>
          <h3 class="article-title">Les silhouettes qui domineront l'automne-hiver 2026</h3>
          <p class="article-excerpt">Des épaules structurées chez Saint Laurent, des volumes extrêmes chez Balenciaga — le tailoring s'impose comme la pièce maîtresse de la saison.</p>
          <a href="#" class="article-link">Lire la suite →</a>
        </article>

        <article class="article-card">
          <div class="article-thumb">
            <img src="/assets/img/magazine/laurent-lafitte.webp" alt="Style masculin cérémonie" />
          </div>
          <span class="article-rubric">Style · 4 min</span>
          <h3 class="article-title">Smoking revisité, velours et boucles d'oreilles : le style homme s'affranchit</h3>
          <p class="article-excerpt">Sur le tapis rouge, les hommes jouent la fluidité et les matières précieuses. Un glissement discret mais net vers une élégance libérée.</p>
          <a href="#" class="article-link">Lire la suite →</a>
        </article>

        <article class="article-card">
          <div class="article-thumb">
            <img src="/assets/img/magazine/frank-dubosc.webp" alt="Entre deux défilés" />
          </div>
          <span class="article-rubric">Style · 3 min</span>
          <h3 class="article-title">Street style : ce que les invités portent entre deux défilés</h3>
          <p class="article-excerpt">Manteaux surdimensionnés, sneakers rares et sacs sculptés — le vrai spectacle se passe parfois en dehors des podiums.</p>
          <a href="#" class="article-link">Lire la suite →</a>
        </article>

      </div>
    </div>
  </section>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>