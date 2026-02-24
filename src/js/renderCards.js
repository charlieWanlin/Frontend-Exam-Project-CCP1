// =====================
// HERO : PAGE CÉLÉBRITÉ
// =====================
export function renderCelebHero(celeb, outfits) {
  const hero = document.querySelector("#celeb-hero");
  if (!hero) return;

  const celebOutfits = outfits.filter((o) => o.celeb === celeb.slug);

  let bioHTML = "";
  if (celeb.bio) {
    bioHTML = `
      <p class="hidden md:block font-serif font-light leading-relaxed text-stone-600 text-[0.95rem] md:text-[1rem] lg:text-[1.05rem] max-w-sm">
        ${celeb.bio}
      </p>
    `;
  }

  hero.innerHTML = `
    <section class="flex flex-col lg:h-[calc(100vh-3.5rem)]">

      <!-- Breadcrumb -->
      <div class="px-5 sm:px-8 lg:px-10 pt-5 pb-2 text-[0.6rem] tracking-[0.2em] uppercase text-stone-400 flex gap-2 items-center shrink-0">
        Stars & Célébrités
        <span class="text-gold">›</span>
        ${celeb.job}
      </div>

      <!-- Row image + contenu -->
      <div class="flex flex-col md:flex-row lg:flex-1 lg:min-h-0 lg:px-10 lg:pb-10">

        <!-- IMAGE -->
        <div class="relative overflow-hidden aspect-[3/4] md:aspect-auto md:w-[45%] lg:w-auto lg:flex-1">
          <img
            src="${celeb.img}"
            alt="${celeb.name}"
            class="w-full h-full object-cover object-top"
            style="filter: sepia(8%) contrast(1.04)"
          />
          <div class="absolute inset-0 pointer-events-none border border-gold/40"></div>
          <div class="absolute bottom-4 left-4 bg-ink text-cream text-[0.6rem] tracking-widest uppercase px-3 py-1.5">
            ${celebOutfits.length} looks
          </div>
        </div>

        <!-- CONTENU -->
        <div class="bg-parchment flex flex-col justify-between relative overflow-hidden px-5 py-7 sm:px-8 sm:py-9 md:w-[55%] md:px-10 md:py-10 lg:flex-1 lg:px-14 lg:py-12">

          <!-- Numéro décoratif -->
          <span class="hidden md:block absolute top-5 right-5 pointer-events-none select-none font-serif font-light leading-none text-gold/15 text-[4rem] md:text-[5rem] lg:text-[7rem]">
            0${celeb.id}
          </span>

          <!-- Tags description -->
          <div class="flex flex-wrap gap-2 md:gap-4 text-[0.58rem] tracking-[0.2em] uppercase text-gold-dark">
            ${celeb.description.map((d) => `<span>${d}</span>`).join('<span class="opacity-30">·</span>')}
          </div>

          <div class="mt-5 md:mt-0">

            <!-- Nom -->
            <h2 class="font-serif font-light leading-[0.92] tracking-tight text-ink text-[2.6rem] sm:text-[3.2rem] md:text-[3.8rem] lg:text-[clamp(3.5rem,4.5vw,5.5rem)] mb-5 md:mb-7 lg:mb-8">
              ${celeb.name.split(" ")[0]}<br />
              <em class="text-gold-dark italic">${celeb.name.split(" ").slice(1).join(" ")}</em>
            </h2>

            <div class="w-8 h-px bg-gold mb-4 md:mb-6 lg:mb-7"></div>

            <!-- Infos -->
            <ul class="flex flex-wrap gap-x-4 gap-y-1.5 md:flex-col md:gap-2 mb-5 md:mb-7 lg:mb-8">
              <li class="flex items-center gap-2.5 text-[0.63rem] tracking-widest uppercase text-stone-500">
                <span class="size-1.5 bg-gold shrink-0"></span>${celebOutfits.length} looks archivés
              </li>
              <li class="flex items-center gap-2.5 text-[0.63rem] tracking-widest uppercase text-stone-500">
                <span class="size-1.5 bg-gold shrink-0"></span>${celeb.job}
              </li>
            </ul>

            <!-- Bio -->
            ${bioHTML}

          </div>

          <!-- CTA -->
          <div class="mt-6 md:mt-0">
            <a href="#looks" class="relative overflow-hidden inline-block bg-ink text-cream no-underline text-[0.6rem] tracking-[0.2em] uppercase px-6 py-3 md:px-7 md:py-3.5 group/btn">
              <span class="relative z-10">Tous ses looks</span>
              <span class="absolute inset-0 bg-gold -translate-x-full group-hover/btn:translate-x-0 transition-transform duration-300 ease-in-out"></span>
            </a>
          </div>

        </div>
      </div>
    </section>
  `;
}

function createCelebrityCard(celebrity, outfits) {
  const celebOutfits = outfits.filter((o) => o.celeb === celebrity.slug);
  console.log(
    "slug :",
    celebrity.slug,
    "| nb looks trouvés :",
    celebOutfits.length,
  );

  const card = document.createElement("article");
  card.className = "group cursor-pointer";
  card.innerHTML = `
    <a href="catalog-outfits.html?celeb=${celebrity.slug}">
      <div class="overflow-hidden aspect-[3/4] bg-gray-100">
        <img
          src="${celebrity.img}"
          alt="${celebrity.name}"
          class="w-full h-full object-cover object-center transition-transform duration-400 group-hover:scale-105"
        />
      </div>
      <div class="pt-4 px-1">
        <h4 class="text-sm tracking-wide text-black">${celebrity.name}</h4>
        <p class="text-xs text-gray-400 font-light mt-1">${celebrity.job}</p>
        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
          <span class="text-xs tracking-widest text-gold uppercase">${celebOutfits.length} pièces</span>
          <span class="text-xs tracking-widest text-gray-300 group-hover:text-black transition-colors duration-200">Voir →</span>
        </div>
      </div>
    </a>
  `;
  return card;
}

// =====================
// CARD : SÉRIE
// =====================
function createSerieCard(serie) {
  const card = document.createElement("article");
  card.className = "group cursor-pointer";
  card.innerHTML = `
    <div class="relative overflow-hidden aspect-[3/4] bg-gray-100">
      <img
        src="${serie.img}"
        alt="${serie.name}"
        class="w-full h-full object-cover object-center transition-all duration-400 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] group-hover:scale-[1.03] group-hover:saturate-[0.80] group-hover:brightness-[0.60]"
      />
      <div class="absolute inset-0 bg-linear-to-t from-black/85 via-black/15 to-transparent transition-opacity duration-400 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] opacity-80 group-hover:opacity-95"></div>
      <div class="absolute bottom-0 left-0 right-0 p-3 md:p-5 transition-transform duration-400 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] translate-y-2 group-hover:translate-y-0">
        <div class="tag-series flex gap-1.5 md:gap-2 flex-wrap mb-2 md:mb-3 transition-opacity duration-400 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] opacity-0 group-hover:opacity-100">
          ${serie.description
            .map(
              (tag) => `
            <span class="text-[8px] md:text-[10px] tracking-widest text-gold font-light border border-gold/60 rounded-sm px-1.5 md:px-2 py-0.5 uppercase">${tag}</span>
          `,
            )
            .join("")}
        </div>
        <h4 class="text-[11px] md:text-sm tracking-wide text-white font-light leading-snug">${serie.name}</h4>
        <p class="text-[9px] md:text-[11px] text-white/50 font-light mt-0.5 tracking-widest uppercase">${serie.platform}</p>
        <div class="flex items-center justify-between mt-2 md:mt-3 pt-2 md:pt-3 border-t border-white/20 transition-opacity duration-400 ease-[cubic-bezier(0.25,0.46,0.45,0.94)] opacity-0 group-hover:opacity-100">
          <span class="text-[8px] md:text-[10px] tracking-widest text-gold uppercase">${serie.looks} pièces</span>
          <span class="text-[8px] md:text-[10px] tracking-widest text-white/60 uppercase">Voir →</span>
        </div>
      </div>
    </div>
  `;
  return card;
}

// =====================
// CARD : TENUE (OUTFIT)
// =====================
function createOutfitCard(outfit) {
  const card = document.createElement("article");
  card.className = "cursor-pointer group";
  card.innerHTML = `
    <a href="${outfit.url}">
      <div class="relative aspect-[3/4] bg-gray-100">
        <img
          src="${outfit.img}"
          alt="${outfit.title}"
          class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105"
        />
        <div class="absolute inset-0 bg-black/20 flex items-end justify-center pb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
          <span class="bg-white text-gray-900 text-xs tracking-widest uppercase px-4 py-2">Voir les looks</span>
        </div>
      </div>
      <div class="mt-3">
        <p class="text-sm font-medium leading-snug">${outfit.title}</p>
        <p class="text-xs text-gray-400 mt-0.5">${outfit.location}</p>
      </div>
    </a>
  `;
  return card;
}

// =====================
// RENDU GÉNÉRIQUE (célébrités + séries)
// =====================
export function renderCards(containerClass, items, type, outfits = []) {
  const containers = document.querySelectorAll(containerClass);
  if (!containers.length) return;

  containers.forEach((container) => {
    container.innerHTML = "";
    items.forEach((item) => {
      let card;
      if (type === "celebrity") {
        card = createCelebrityCard(item, outfits);
      } else if (type === "serie") {
        card = createSerieCard(item);
      }
      container.appendChild(card);
    });
  });
}

// =====================
// RENDU SPÉCIFIQUE : OUTFITS
// =====================
// Gère l'affichage et le filtrage par tags (non affichés dans la card)
export function renderOutfitCards(containerClass, items, activeFilter = "all") {
  const containers = document.querySelectorAll(containerClass);
  if (!containers.length) return;

  const filtered =
    activeFilter === "all"
      ? items
      : items.filter((outfit) => outfit.tags.includes(activeFilter));

  containers.forEach((container) => {
    container.innerHTML = "";
    filtered.forEach((outfit) => {
      container.appendChild(createOutfitCard(outfit));
    });
  });
}
