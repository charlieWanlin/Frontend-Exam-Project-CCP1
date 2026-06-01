
<?php require_once __DIR__ . '/../partials/header.php'; ?>
<!-- ══════════════════════════════════════════
     Hero vidéo
══════════════════════════════════════════ -->
<div class="relative overflow-hidden" style="height: calc(100vh - 56px)">
    <video autoplay muted loop playsinline class="w-full h-full object-cover">
        <source src="/assets/videos/hero-video.mp4" type="video/mp4" />
    </video>
    <div class="w-full h-10 bg-white absolute bottom-0 left-0"></div>
</div>

<!-- ══════════════════════════════════════════
     SECTION PRÉSENTATION
══════════════════════════════════════════ -->
<section class="presentation-landing-page bg-bg-warm py-20 md:py-0 md:min-h-screen md:flex md:items-center">
    <div class="w-full px-4 md:px-10 xl:px-16 md:grid md:grid-cols-2 md:gap-16 md:items-center">

        <div class="flex flex-col gap-6">
            <p class="text-xs tracking-widest text-gray-400 uppercase hidden md:block">
                Une nouvelle façon de découvrir votre style
            </p>
            <h2 class="text-4xl font-normal md:text-5xl xl:text-6xl leading-snug tracking-tight">
                Identifiez le look de
                <span class="text-gold italic">vos stars</span>
            </h2>
            <p class="text-sm text-gray-400 font-light leading-loose md:text-sm md:max-w-sm">
                Uploadez une photo de votre tenue ou de celle d'une célébrité, et
                notre IA de recherche visuelle vous aidera à trouver des vêtements
                similaires, à découvrir les marques et à acheter les pièces que vous aimez.
            </p>
            <div class="flex flex-col gap-3 sm:flex-row sm:gap-4 mt-2">
                <a href="/style-finder"
                   class="inline-block bg-black text-white px-8 py-3.5 text-xs tracking-widest hover:opacity-75 transition-opacity duration-300 text-center">
                    ANALYSER UNE PHOTO
                </a>
                <a href="/celebrities"
                   class="inline-block border border-gray-200 text-gray-500 px-8 py-3.5 text-xs tracking-widest hover:border-black hover:text-black transition-colors duration-300 text-center">
                    VOIR DES EXEMPLES
                </a>
            </div>
            <div class="hidden md:flex gap-12 mt-6 pt-6 border-t border-gray-100">
                <div>
                    <p class="text-xl font-normal text-black">50 000+</p>
                    <p class="text-xs tracking-widest text-gray-400 uppercase mt-1.5">Analyses</p>
                </div>
                <div>
                    <p class="text-xl font-normal text-black">200</p>
                    <p class="text-xs tracking-widest text-gray-400 uppercase mt-1.5">Marques</p>
                </div>
                <div>
                    <p class="text-xl font-normal text-gold">98%</p>
                    <p class="text-xs tracking-widest text-gray-400 uppercase mt-1.5">Précision</p>
                </div>
            </div>
            <div class="flex gap-10 mt-4 text-gold text-sm tracking-widest md:hidden">
                <p>Simple</p>
                <p>Rapide</p>
                <p>Gratuit</p>
            </div>
        </div>

        <div class="image-ia-scanner mt-12 md:mt-0">
            <div class="relative w-full">
                <img src="/assets/img/landing page/Analyse sydney.png"
                     alt="Scanner IA"
                     class="w-full h-auto object-cover block" />
                <div class="absolute flex items-center gap-2 top-[28%] right-[25%]">
                    <span class="bg-gold rounded-full w-6 h-6 flex justify-center items-center text-white text-xs font-medium shrink-0">1</span>
                    <span class="bg-black text-xs text-white p-1.5 whitespace-nowrap">Chemise</span>
                </div>
                <div class="absolute flex items-center gap-2 top-[38%] left-[29%]">
                    <span class="bg-black text-xs text-white p-1.5 whitespace-nowrap">Brassière</span>
                    <span class="bg-gold rounded-full w-6 h-6 flex justify-center items-center text-white text-xs font-medium shrink-0">2</span>
                </div>
                <div class="absolute flex items-center gap-2 bottom-[28%] right-[30%]">
                    <span class="bg-gold rounded-full w-6 h-6 flex justify-center items-center text-white text-xs font-medium shrink-0">3</span>
                    <span class="bg-black text-xs text-white p-1.5 whitespace-nowrap">Pantalon</span>
                </div>
                <div class="absolute flex items-center gap-2 bottom-[8%] right-[26%]">
                    <span class="bg-gold rounded-full w-6 h-6 flex justify-center items-center text-white text-xs font-medium shrink-0">4</span>
                    <span class="bg-black text-xs text-white p-1.5 whitespace-nowrap">Chaussures</span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ══════════════════════════════════════════
     SECTION STEPS
══════════════════════════════════════════ -->
<section class="steps-section bg-white py-20 px-4 md:px-10 xl:px-16">
    <p class="text-xs tracking-widest text-gray-400 uppercase text-center mb-3">
        Comment ça marche
    </p>
    <h3 class="text-center text-lg font-normal tracking-widest uppercase mb-14 text-black">
        Simple. Rapide. Gratuit.
    </h3>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3 md:gap-8">
        <div class="step-card border border-gray-100 p-8 hover:border-gray-200 transition-colors duration-300">
            <p class="text-2xl font-light text-gold mb-6 tracking-widest">01</p>
            <h4 class="text-xs font-semibold tracking-widest uppercase mb-3 text-black">Uploadez votre photo</h4>
            <p class="text-xs text-gray-400 font-light leading-loose">
                Importez une photo de la tenue d'une célébrité, d'une série, ou même de votre propre garde-robe.
            </p>
            <a href="/style-finder"
               class="inline-block mt-6 text-xs tracking-widest text-gold hover:opacity-60 transition-opacity duration-200 uppercase">
                Commencer →
            </a>
        </div>
        <div class="step-card border border-gray-100 p-8 hover:border-gray-200 transition-colors duration-300">
            <p class="text-2xl font-light text-gold mb-6 tracking-widest">02</p>
            <h4 class="text-xs font-semibold tracking-widest uppercase mb-3 text-black">L'IA détecte chaque pièce</h4>
            <p class="text-xs text-gray-400 font-light leading-loose">
                Notre intelligence artificielle identifie chaque vêtement, accessoire et chaussure en quelques secondes.
            </p>
            <a href="#"
               class="inline-block mt-6 text-xs tracking-widest text-gold hover:opacity-60 transition-opacity duration-200 uppercase">
                En savoir plus →
            </a>
        </div>
        <div class="step-card border border-gray-100 p-8 hover:border-gray-200 transition-colors duration-300">
            <p class="text-2xl font-light text-gold mb-6 tracking-widest">03</p>
            <h4 class="text-xs font-semibold tracking-widest uppercase mb-3 text-black">Shoppez à votre budget</h4>
            <p class="text-xs text-gray-400 font-light leading-loose">
                Trouvez les pièces exactes ou des alternatives similaires, adaptées à tous les budgets.
            </p>
            <a href="#"
               class="inline-block mt-6 text-xs tracking-widest text-gold hover:opacity-60 transition-opacity duration-200 uppercase">
                Voir les résultats →
            </a>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     SECTION CÉLÉBRITÉS — dynamique BDD
══════════════════════════════════════════ -->
<section class="celebrities-section bg-bg-warm py-20 px-4 md:px-10 xl:px-16">
    <div class="flex items-end justify-between mb-10">
        <div>
            <p class="text-xs tracking-widest text-gray-400 uppercase mb-2">Inspirations</p>
            <h3 class="text-2xl font-normal md:text-3xl leading-snug">
                Leurs looks,<br>
                <span class="text-gold italic">votre garde-robe</span>
            </h3>
        </div>
        <a href="/celebrities"
           class="hidden md:block text-xs tracking-widest text-gray-300 uppercase hover:text-black transition-colors duration-300">
            Voir tout →
        </a>
    </div>

    <div class="celebrities-grid grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
        <?php foreach ($celebrites as $celeb) : ?>

            <article class="group cursor-pointer">
                <a href="/celebrities/<?= htmlspecialchars($celeb['slug']) ?>">

                    <!-- Image -->
                    <div class="overflow-hidden aspect-3/4 bg-gray-100">
                        <img
                            src="/assets/img/<?= htmlspecialchars($celeb['photo']) ?>"
                            alt="<?= htmlspecialchars($celeb['nom']) ?>"
                            class="w-full h-full object-cover object-center transition-transform duration-400 group-hover:scale-105"
                        />
                    </div>

                    <!-- Infos sous l'image -->
                    <div class="pt-4 px-1">
                        <h4 class="text-sm tracking-wide text-black">
                            <?= htmlspecialchars($celeb['nom']) ?>
                        </h4>
                        <p class="text-xs text-gray-400 font-light mt-1">
                            <?= htmlspecialchars($celeb['categorie'] ?? '') ?>
                        </p>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                            <span class="text-xs tracking-widest text-gold uppercase">
                                <?= (int)$celeb['nb_looks'] ?> looks
                            </span>
                            <span class="text-xs tracking-widest text-gray-300 group-hover:text-black transition-colors duration-200">
                                Voir →
                            </span>
                        </div>
                    </div>

                </a>
            </article>

        <?php endforeach; ?>

        <?php if (empty($celebrites)) : ?>
            <p class="text-gray-400 text-sm col-span-4 text-center py-10">
                Aucune célébrité pour l'instant.
            </p>
        <?php endif; ?>
    </div>

    <div class="mt-8 text-center md:hidden">
        <a href="/celebrities"
           class="text-xs tracking-widest text-gray-400 uppercase hover:text-black transition-colors duration-200">
            Voir toutes les célébrités →
        </a>
    </div>
</section>

<!-- ══════════════════════════════════════════
     SECTION SÉRIES — dynamique BDD
══════════════════════════════════════════ -->
<section class="series-section bg-white py-20 px-4 md:px-10 xl:px-16">
    <div class="flex items-end justify-between mb-10">
        <div>
            <p class="text-xs tracking-widest text-gray-400 uppercase mb-2">Collections</p>
            <h3 class="text-2xl font-normal md:text-3xl leading-snug">
                Les looks des séries,<br>
                <span class="text-gold italic">votre prochaine tenue</span>
            </h3>
        </div>
        <a href="/series"
           class="hidden md:block text-xs tracking-widest text-gray-300 uppercase hover:text-black transition-colors duration-300">
            Voir tout →
        </a>
    </div>

    <div class="series-grid grid grid-cols-2 gap-4 md:grid-cols-4 md:gap-6">
        <?php foreach ($series as $serie) : ?>

            <article class="group cursor-pointer">
                <a href="/series/<?= htmlspecialchars($serie['slug']) ?>">

                    <div class="relative overflow-hidden aspect-3/4 bg-gray-100">

                        <!-- Image -->
                        <img
                            src="/assets/img/<?= htmlspecialchars($serie['photo']) ?>"
                            alt="<?= htmlspecialchars($serie['nom']) ?>"
                            class="w-full h-full object-cover object-center transition-all duration-400 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] group-hover:scale-[1.03] group-hover:saturate-[0.80] group-hover:brightness-[0.60]"
                        />

                        <!-- Overlay gradient permanent -->
                        <div class="absolute inset-0 bg-linear-to-t from-black/85 via-black/15 to-transparent transition-opacity duration-400 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] opacity-80 group-hover:opacity-95"></div>

                        <!-- Contenu bas de card -->
                        <div class="absolute bottom-0 left-0 right-0 p-3 md:p-5 transition-transform duration-400 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] translate-y-2 group-hover:translate-y-0">

                            <!-- Tags style — visibles au hover -->
                            <div class="flex gap-1.5 md:gap-2 flex-wrap mb-2 md:mb-3 transition-opacity duration-400 opacity-0 group-hover:opacity-100">
                                <?php if (!empty($serie['style'])) : ?>
                                    <span class="text-[8px] md:text-[10px] tracking-widest text-gold font-light border border-gold/60 px-1.5 md:px-2 py-0.5 uppercase">
                                        <?= htmlspecialchars($serie['style']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Nom série -->
                            <h4 class="text-[11px] md:text-sm tracking-wide text-white font-light leading-snug">
                                <?= htmlspecialchars($serie['nom']) ?>
                            </h4>

                            <!-- Nb looks + CTA — visibles au hover -->
                            <div class="flex items-center justify-between mt-2 md:mt-3 pt-2 md:pt-3 border-t border-white/20 transition-opacity duration-400 opacity-0 group-hover:opacity-100">
                                <span class="text-[8px] md:text-[10px] tracking-widest text-gold uppercase">
                                    <?= (int)$serie['nb_looks'] ?> looks
                                </span>
                                <span class="text-[8px] md:text-[10px] tracking-widest text-white/60 uppercase">
                                    Voir →
                                </span>
                            </div>

                        </div>
                    </div>

                </a>
            </article>

        <?php endforeach; ?>

        <?php if (empty($series)) : ?>
            <p class="text-gray-400 text-sm col-span-4 text-center py-10">
                Aucune série pour l'instant.
            </p>
        <?php endif; ?>
    </div>

    <div class="mt-8 text-center md:hidden">
        <a href="/series"
           class="text-xs tracking-widest text-gray-400 uppercase hover:text-black transition-colors duration-200">
            Voir toutes les séries →
        </a>
    </div>
</section>

<!-- ══════════════════════════════════════════
     CTA BANNER
══════════════════════════════════════════ -->
<section class="cta-banner bg-black py-28 px-4 md:px-10 xl:px-16 text-center">
    <p class="text-xs tracking-widest text-gray-600 uppercase mb-4">Prêt à commencer ?</p>
    <h3 class="text-white text-2xl font-normal md:text-4xl leading-snug tracking-tight mb-10">
        Trouvez votre style dès maintenant
    </h3>
    <a href="/style-finder"
       class="inline-block border border-white text-white px-10 py-4 text-xs tracking-widest hover:bg-white hover:text-black transition-colors duration-300">
        COMMENCER
    </a>
</section>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>