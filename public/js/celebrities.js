/**
 * celebrities.js
 * ──────────────────────────────────────────────
 * Gestion du catalogue des célébrités :
 *   - Récupération via l'API  GET /api/celebrities
 *   - Filtres par catégorie
 *   - Tri
 *   - Pagination
 * ──────────────────────────────────────────────
 */

(function () {

  /* ═══════════════════════════════════════════
     1. ÉTAT
     Les variables qui changent au fil des actions
     de l'utilisateur.
  ═══════════════════════════════════════════ */
  let activeCategorie = 'tous';       // filtre actif
  let activeSort      = 'popularite'; // tri actif
  let page            = 1;            // page courante
  let totalPages      = 1;            // calculé après chaque fetch
  const PAGE_SIZE     = 16;           // nombre de cartes par page
  let isLoading       = false;        // évite les doubles appels


  /* ═══════════════════════════════════════════
     2. RÉFÉRENCES AU DOM
     Toutes les références sont regroupées dans
     un seul objet DOM.
     → accès via DOM.sortMenu, DOM.grid, etc.
     → pratique à déboguer : console.log(DOM)
  ═══════════════════════════════════════════ */
  const DOM = {
    grid:         document.getElementById('celebrities-grid'),
    empty:        document.getElementById('celeb-empty'),
    countEl:      document.getElementById('celebs-count'),
    sortBtn:      document.getElementById('sort-btn'),
    sortMenu:     document.getElementById('sort-menu'),
    sortLabel:    document.getElementById('sort-label'),
    filterToggle: document.getElementById('filter-toggle'),
    filterMenu:   document.getElementById('filter-menu'),
    pagination:   document.getElementById('pagination'),
    pageNumbers:  document.getElementById('page-numbers'),
    pagePrev:     document.getElementById('page-prev'),
    pageNext:     document.getElementById('page-next'),
    catalogue:    document.getElementById('section-catalogue'),
  };


  /* ═══════════════════════════════════════════
     3. UTILITAIRES
  ═══════════════════════════════════════════ */

  /** Échappe les caractères HTML pour éviter les injections XSS */
  function escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /** Fait défiler vers le haut du catalogue */
  function scrollToCatalogue() {
    DOM.catalogue.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }


  /* ═══════════════════════════════════════════
     4. RENDU DES CARTES
     Génère le HTML d'une carte à partir d'un
     objet célébrité retourné par l'API.
  ═══════════════════════════════════════════ */
  function renderCard(c) {
    return `
      <article class="celeb-card cursor-pointer" role="listitem">
        <a href="/celebrities/${escHtml(c.slug)}">
          <div class="overflow-hidden" style="aspect-ratio:3/4;background:#f0ede8">
            <img
              src="/assets/img/${escHtml(c.photo)}"
              alt="${escHtml(c.nom)}"
              class="celeb-img w-full h-full object-cover object-top"
              loading="lazy"
            />
          </div>
          <div class="pt-4 px-1">
            <h3 class="text-sm tracking-wide text-[#1a1a1a] font-body">${escHtml(c.nom)}</h3>
            ${c.categorie
              ? `<p class="text-xs text-muted font-light mt-1 font-body">${escHtml(c.categorie)}</p>`
              : ''}
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-border">
              <span class="looks-count">${parseInt(c.nb_looks, 10)} looks</span>
              <span class="celeb-arrow">Voir →</span>
            </div>
          </div>
        </a>
      </article>`;
  }


  /* ═══════════════════════════════════════════
     5. SKELETONS
     Affiche des placeholders pendant le chargement
     pour éviter un écran vide.
  ═══════════════════════════════════════════ */
  function showSkeletons() {
    DOM.grid.innerHTML = Array(8)
      .fill('<div class="skeleton rounded-none" role="listitem"></div>')
      .join('');
  }


  /* ═══════════════════════════════════════════
     6. PAGINATION
     Calcule le nombre de pages et affiche les
     boutons numérotés avec ellipses.
  ═══════════════════════════════════════════ */
  function renderPagination(total) {
    totalPages = Math.ceil(total / PAGE_SIZE);

    // Cache la pagination si une seule page
    DOM.pagination.classList.toggle('hidden', totalPages <= 1);

    DOM.pagePrev.disabled = (page <= 1);
    DOM.pageNext.disabled = (page >= totalPages);

    // Fenêtre glissante : on affiche au max 5 numéros
    let start = Math.max(1, page - 2);
    let end   = Math.min(totalPages, start + 4);
    if (end - start < 4) start = Math.max(1, end - 4);

    DOM.pageNumbers.innerHTML = '';

    // Première page + ellipse gauche
    if (start > 1) {
      DOM.pageNumbers.insertAdjacentHTML('beforeend', makePageBtn(1));
      if (start > 2) {
        DOM.pageNumbers.insertAdjacentHTML('beforeend',
          `<span class="text-muted text-xs px-1 self-center">…</span>`);
      }
    }

    // Pages de la fenêtre
    for (let i = start; i <= end; i++) {
      DOM.pageNumbers.insertAdjacentHTML('beforeend', makePageBtn(i));
    }

    // Ellipse droite + dernière page
    if (end < totalPages) {
      if (end < totalPages - 1) {
        DOM.pageNumbers.insertAdjacentHTML('beforeend',
          `<span class="text-muted text-xs px-1 self-center">…</span>`);
      }
      DOM.pageNumbers.insertAdjacentHTML('beforeend', makePageBtn(totalPages));
    }

    // On attache les clics sur les numéros fraîchement créés
    DOM.pageNumbers.querySelectorAll('.page-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        page = parseInt(btn.dataset.p, 10);
        fetchCelebrities();
        scrollToCatalogue();
      });
    });
  }

  /** Crée le HTML d'un bouton de page numérotée */
  function makePageBtn(n) {
    const isActive = (n === page) ? ' active' : '';
    return `<button class="page-btn${isActive}" data-p="${n}">${n}</button>`;
  }


  /* ═══════════════════════════════════════════
     7. FETCH PRINCIPAL
     Appelle l'API, affiche les résultats ou
     un message d'erreur.
  ═══════════════════════════════════════════ */
  async function fetchCelebrities() {
    if (isLoading) return; // évite les appels simultanés
    isLoading = true;

    // Prépare l'affichage
    showSkeletons();
    DOM.empty.classList.add('hidden');
    DOM.pagination.classList.add('hidden');

    // Construction de l'URL avec les paramètres actifs
    const params = new URLSearchParams();
    if (activeCategorie !== 'tous') params.set('categorie', activeCategorie);
    params.set('sort',     activeSort);
    params.set('page',     String(page));
    params.set('per_page', String(PAGE_SIZE));

    try {
      const res  = await fetch(`/api/celebrities?${params}`);
      if (!res.ok) throw new Error(`Erreur HTTP ${res.status}`);

      const data  = await res.json();
      const list  = Array.isArray(data?.items) ? data.items : [];
      const total = typeof data?.total === 'number' ? data.total : 0;

      DOM.grid.innerHTML = ''; // vide les skeletons

      if (!list.length) {
        DOM.empty.classList.remove('hidden');
        DOM.countEl.textContent = '0';
        return;
      }

      // Injection des cartes
      DOM.grid.insertAdjacentHTML('beforeend', list.map(renderCard).join(''));
      DOM.countEl.textContent = String(total);
      renderPagination(total);

    } catch (err) {
      DOM.grid.innerHTML = `
        <p class="text-muted text-xs col-span-full text-center py-10 font-body">
          Une erreur est survenue. Veuillez réessayer.
        </p>`;
      console.error('[celebrities.js]', err);
    } finally {
      isLoading = false;
    }
  }


  /* ═══════════════════════════════════════════
     8. ÉVÉNEMENTS — FILTRES PAR CATÉGORIE
     Gère les boutons "Tous / Cinéma / Chant …"
     (desktop + mobile en même temps).
  ═══════════════════════════════════════════ */
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      // Désactive tous les boutons
      document.querySelectorAll('.filter-btn').forEach(b => {
        b.classList.remove('active');
        b.setAttribute('aria-pressed', 'false');
      });
      // Active celui cliqué
      btn.classList.add('active');
      btn.setAttribute('aria-pressed', 'true');

      activeCategorie = btn.dataset.filter || 'tous';
      page = 1; // retour page 1 à chaque changement de filtre
      fetchCelebrities();
    });
  });


  /* ═══════════════════════════════════════════
     9. ÉVÉNEMENTS — TOGGLE FILTRES MOBILE
  ═══════════════════════════════════════════ */
  DOM.filterToggle?.addEventListener('click', e => {
    e.stopPropagation(); // empêche la fermeture immédiate par le listener global
    const isOpen = !DOM.filterMenu.classList.contains('hidden');
    DOM.filterMenu.classList.toggle('hidden');
    DOM.filterToggle.setAttribute('aria-expanded', String(!isOpen));
  });


  /* ═══════════════════════════════════════════
     10. ÉVÉNEMENTS — TRI
  ═══════════════════════════════════════════ */
  DOM.sortBtn?.addEventListener('click', e => {
    e.stopPropagation();
    const isOpen = !DOM.sortMenu.classList.contains('hidden');
    DOM.sortMenu.classList.toggle('hidden');
    DOM.sortBtn.setAttribute('aria-expanded', String(!isOpen));
  });

  DOM.sortMenu?.querySelectorAll('.sort-opt').forEach(opt => {
    opt.addEventListener('click', e => {
      e.stopPropagation();

      // Met à jour le style de l'option active
      DOM.sortMenu.querySelectorAll('.sort-opt').forEach(o => {
        o.classList.remove('text-[#1a1a1a]', 'font-medium');
      });
      opt.classList.add('text-[#1a1a1a]', 'font-medium');

      activeSort = opt.dataset.sort;
      DOM.sortLabel.textContent = opt.textContent.trim();
      DOM.sortMenu.classList.add('hidden');
      DOM.sortBtn.setAttribute('aria-expanded', 'false');

      page = 1;
      fetchCelebrities();
    });
  });


  /* ═══════════════════════════════════════════
     11. ÉVÉNEMENTS — PAGINATION PREV / NEXT
  ═══════════════════════════════════════════ */
  DOM.pagePrev?.addEventListener('click', () => {
    if (page > 1) {
      page--;
      fetchCelebrities();
      scrollToCatalogue();
    }
  });

  DOM.pageNext?.addEventListener('click', () => {
    if (page < totalPages) {
      page++;
      fetchCelebrities();
      scrollToCatalogue();
    }
  });


  /* ═══════════════════════════════════════════
     12. FERMETURE DES MENUS AU CLIC EXTÉRIEUR
  ═══════════════════════════════════════════ */
  document.addEventListener('click', () => {
    DOM.sortMenu?.classList.add('hidden');
    DOM.sortBtn?.setAttribute('aria-expanded', 'false');
    DOM.filterMenu?.classList.add('hidden');
    DOM.filterToggle?.setAttribute('aria-expanded', 'false');
  });


  /* ═══════════════════════════════════════════
     13. INITIALISATION
     Lance le premier appel API au chargement.
  ═══════════════════════════════════════════ */
  fetchCelebrities();

})(); // IIFE : évite de polluer le scope global