<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($title ?? 'LE DRESSING') ?></title>
  <link rel="stylesheet" href="/css/output.css" />
</head>
<body class="scrollbar-hide bg-white pt-15">

<nav id="navbar" class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-4 py-4 bg-white border-b border-[var(--color-border)] transition-transform duration-300 md:px-10 xl:px-16">

  <button id="mobile-menu-btn" class="flex cursor-pointer md:hidden" aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu">
    <svg width="20" height="14" viewBox="0 0 20 14" fill="none">
      <line x1="0" y1="1"  x2="20" y2="1"  stroke="black" stroke-width="1.5" stroke-linecap="round"/>
      <line x1="3" y1="7"  x2="20" y2="7"  stroke="black" stroke-width="1.5" stroke-linecap="round"/>
      <line x1="6" y1="13" x2="20" y2="13" stroke="black" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
  </button>

  <a href="/" class="absolute left-1/2 -translate-x-1/2 font-black text-xl tracking-widest text-black md:static md:translate-x-0" style="font-family: var(--font-heading)">
    LE DRESSING
  </a>

  <ul class="hidden md:flex md:gap-6 xl:gap-10">
    <?php foreach ([
      '/celebrities' => 'CÉLÉBRITÉS',
      '/series'      => 'SÉRIES',
      '/style-finder'=> 'STYLE FINDER',
      '/magazine'    => 'MAGAZINE',
    ] as $href => $label): ?>
      <li>
        <a class="text-[var(--color-muted)] hover:text-black text-xs tracking-widest transition-colors duration-200" href="<?= $href ?>">
          <?= $label ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>

  <div class="flex items-center gap-4 md:gap-5">

    <button id="search-btn" class="hover:opacity-60 cursor-pointer transition-opacity" aria-label="Rechercher">
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
        <circle cx="7.5" cy="7.5" r="5.5" stroke="black" stroke-width="1.5"/>
        <line x1="11.5" y1="11.5" x2="17" y2="17" stroke="black" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </button>

    <?php $accountHref = !empty($_SESSION['user_id']) ? '/mon-compte' : '/login'; ?>
    <a href="<?= $accountHref ?>" class="hover:opacity-60 transition-opacity" aria-label="Mon compte">
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
        <circle cx="9" cy="6" r="3.5" stroke="black" stroke-width="1.5"/>
        <path d="M1.5 17C1.5 13.134 4.91 10 9 10C13.09 10 16.5 13.134 16.5 17" stroke="black" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </a>

    <a href="/favoris" class="hidden sm:flex hover:opacity-60 transition-opacity" aria-label="Mes favoris">
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
        <path d="M9 15.5C9 15.5 1.5 10.5 1.5 5.5C1.5 3.015 3.515 1 6 1C7.38 1 8.6 1.65 9 2.5C9.4 1.65 10.62 1 12 1C14.485 1 16.5 3.015 16.5 5.5C16.5 10.5 9 15.5 9 15.5Z" stroke="black" stroke-width="1.5" stroke-linejoin="round"/>
      </svg>
    </a>

    <?php
      $panierCount = 0;
      if (!empty($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $item) {
          $panierCount += (int)($item['quantite'] ?? 1);
        }
      }
    ?>
    <a href="/panier" class="relative inline-flex hover:opacity-60 transition-opacity" aria-label="Mon panier">
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
        <path d="M6 7V5C6 3.343 7.343 2 9 2C10.657 2 12 3.343 12 5V7" stroke="black" stroke-width="1.5" stroke-linecap="round"/>
        <rect x="2" y="7" width="14" height="9" rx="1.5" stroke="black" stroke-width="1.5"/>
      </svg>
      <?php if ($panierCount > 0): ?>
        <span class="absolute -top-1.5 -right-1.5 bg-[var(--color-gold)] text-white text-[9px] font-medium w-4 h-4 rounded-full flex items-center justify-center">
          <?= $panierCount > 9 ? '9+' : $panierCount ?>
        </span>
      <?php endif; ?>
    </a>

  </div>
</nav>

<div id="overlay" class="fixed inset-0 bg-black/40 z-40 hidden opacity-0 transition-opacity duration-300"></div>

<div id="search-sidebar" class="fixed top-0 right-0 z-50 flex flex-col w-full h-full bg-white translate-x-full transition-transform duration-300 md:w-[420px]" role="search" aria-label="Recherche">
  <div class="flex items-center gap-3 px-6 py-5 border-b border-[var(--color-border)] shrink-0">
    <svg width="14" height="14" viewBox="0 0 18 18" fill="none">
      <circle cx="7.5" cy="7.5" r="5.5" stroke="#9ca3af" stroke-width="1.5"/>
      <line x1="11.5" y1="11.5" x2="17" y2="17" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
    <input id="search-input" type="search" autocomplete="off" placeholder="Rechercher une célébrité, une série..." class="flex-1 outline-none text-sm text-gray-800 placeholder:text-gray-400" aria-label="Rechercher" />
    <button id="search-close" class="text-gray-400 hover:text-gray-900 transition-colors cursor-pointer w-8 h-8 flex items-center justify-center" aria-label="Fermer">
      <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
        <line x1="1" y1="1" x2="11" y2="11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        <line x1="11" y1="1" x2="1" y2="11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </button>
  </div>
  <div id="search-suggestions" class="flex-1 overflow-y-auto px-4 py-4">
    <p class="text-[10px] tracking-widest text-gray-300 uppercase px-2 mb-3">Suggestions</p>
    <div class="flex flex-wrap gap-2 px-2">
      <?php foreach (['/celebrities'=>'Célébrités','/series'=>'Séries','/style-finder'=>'Style Finder'] as $h=>$l): ?>
        <a href="<?= $h ?>" class="text-xs text-[var(--color-muted)] border border-[var(--color-border)] px-3 py-1.5 hover:border-black hover:text-black transition-colors duration-200"><?= $l ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<div id="mobile-menu" class="fixed inset-0 z-40 flex-col pt-20 pb-8 bg-white hidden md:hidden overflow-y-auto">
  <nav class="flex flex-col px-8">
    <?php foreach ([
      '/celebrities'  => 'Célébrités',
      '/series'       => 'Séries',
      '/style-finder' => 'Style Finder',
      '/magazine'     => 'Magazine',
      '/favoris'      => 'Mes favoris',
      '/panier'       => 'Mon panier',
    ] as $h => $l): ?>
      <a href="<?= $h ?>" class="text-sm tracking-widest uppercase border-b border-[var(--color-border)] py-4 text-gray-700 hover:text-black transition-colors"><?= $l ?></a>
    <?php endforeach; ?>
  </nav>
  <div class="px-8 mt-8">
    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="/mon-compte" class="block w-full bg-black text-white py-3.5 text-xs tracking-widest uppercase text-center hover:opacity-80 transition-opacity">Mon compte</a>
      <a href="/logout"     class="block w-full border border-black text-black py-3.5 text-xs tracking-widest uppercase text-center mt-3 hover:bg-black hover:text-white transition-colors duration-200">Se déconnecter</a>
    <?php else: ?>
      <a href="/login"    class="block w-full bg-black text-white py-3.5 text-xs tracking-widest uppercase text-center hover:opacity-80 transition-opacity">Se connecter</a>
      <a href="/register" class="block w-full border border-black text-black py-3.5 text-xs tracking-widest uppercase text-center mt-3 hover:bg-black hover:text-white transition-colors duration-200">Créer un compte</a>
    <?php endif; ?>
  </div>
</div>

<script src="/js/header.js" defer></script>