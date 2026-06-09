<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?? 'LE DRESSING' ?></title>
    <link rel="stylesheet" href="/css/output.css" />
</head>
<body class="scrollbar-hide bg-white pt-[65px]">

<!-- NAVBAR -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-4 py-4 bg-white border-b border-gray-100 transition-transform duration-300 md:px-10 xl:px-16">

    <!-- Hamburger mobile -->
    <button id="mobile-menu-btn" class="flex flex-col gap-1 cursor-pointer md:hidden" aria-label="Menu">
        <span class="block w-5 h-0.5 bg-black"></span>
        <span class="block w-5 h-0.5 bg-black"></span>
        <span class="block w-5 h-0.5 bg-black"></span>
    </button>

    <!-- Logo -->
    <a href="/" class="absolute left-1/2 -translate-x-1/2 text-black font-black font-source-serif text-xl tracking-widest md:static md:translate-x-0 lg:text-xl">
        LE DRESSING
    </a>

    <!-- Nav links desktop -->
    <ul class="hidden md:flex md:gap-6 xl:gap-10">
        <li><a class="text-gray-400 hover:text-black text-xs tracking-widest transition-colors duration-200" href="/celebrities">CÉLÉBRITÉS</a></li>
        <li><a class="text-gray-400 hover:text-black text-xs tracking-widest transition-colors duration-200" href="/series">SÉRIES</a></li>
        <li><a class="text-gray-400 hover:text-black text-xs tracking-widest transition-colors duration-200" href="/style-finder">STYLE FINDER</a></li>
        <li><a class="text-gray-400 hover:text-black text-xs tracking-widest transition-colors duration-200" href="/magazine">MAGAZINE</a></li>
    </ul>

    <!-- Icons -->
    <div class="flex items-center gap-4 md:gap-5 xl:gap-6">
        <button id="search-btn" class="hover:opacity-60 cursor-pointer transition-opacity duration-200">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.82299 10.883C8.71242 11.725 7.32362 12.114 5.93727 11.9713C4.55092 11.8287 3.27042 11.1651 2.35456 10.1146C1.4387 9.06411 0.955777 7.70512 1.0034 6.31227C1.05102 4.91942 1.62563 3.59658 2.6111 2.61111C3.59657 1.62564 4.9194 1.05103 6.31225 1.00341C7.70511 0.955789 9.0641 1.43871 10.1146 2.35457C11.165 3.27043 11.8287 4.55093 11.9713 5.93728C12.1139 7.32363 11.725 8.71243 10.883 9.823L15.603 14.543L14.543 15.603L9.82299 10.883ZM10.5 6.5C10.5 7.56087 10.0786 8.57828 9.32842 9.32843C8.57827 10.0786 7.56086 10.5 6.49999 10.5C5.43912 10.5 4.42171 10.0786 3.67156 9.32843C2.92142 8.57828 2.49999 7.56087 2.49999 6.5C2.49999 5.43914 2.92142 4.42172 3.67156 3.67157C4.42171 2.92143 5.43912 2.5 6.49999 2.5C7.56086 2.5 8.57827 2.92143 9.32842 3.67157C10.0786 4.42172 10.5 5.43914 10.5 6.5Z" fill="black"/>
            </svg>
        </button>
        <button id="compte-btn" class="hover:opacity-60 cursor-pointer transition-opacity duration-200">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_123_3189)">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 4C12 5.06087 11.5786 6.07828 10.8284 6.82843C10.0783 7.57857 9.06087 8 8 8C6.93913 8 5.92172 7.57857 5.17157 6.82843C4.42143 6.07828 4 5.06087 4 4C4 2.93913 4.42143 1.92172 5.17157 1.17157C5.92172 0.421427 6.93913 0 8 0C9.06087 0 10.0783 0.421427 10.8284 1.17157C11.5786 1.92172 12 2.93913 12 4ZM10.5 4C10.5 4.66304 10.2366 5.29893 9.76777 5.76777C9.29893 6.23661 8.66304 6.5 8 6.5C7.33696 6.5 6.70107 6.23661 6.23223 5.76777C5.76339 5.29893 5.5 4.66304 5.5 4C5.5 3.33696 5.76339 2.70107 6.23223 2.23223C6.70107 1.76339 7.33696 1.5 8 1.5C8.66304 1.5 9.29893 1.76339 9.76777 2.23223C10.2366 2.70107 10.5 3.33696 10.5 4ZM8 9.5C6.112 9.5 4.174 9.701 2.681 10.355C1.926 10.685 1.251 11.148 0.763 11.79C0.268 12.444 0 13.24 0 14.17V16H1.5V14.17C1.5 13.544 1.674 13.072 1.958 12.697C2.25 12.313 2.69 11.989 3.283 11.729C4.487 11.202 6.174 11 8 11C9.833 11 11.518 11.182 12.721 11.7C13.312 11.954 13.751 12.274 14.04 12.66C14.323 13.035 14.5 13.519 14.5 14.171V16H16V14.171C16 13.223 15.735 12.416 15.239 11.757C14.749 11.107 14.071 10.647 13.314 10.321C11.82 9.678 9.88 9.5 8 9.5Z" fill="black"/>
                </g>
                <defs>
                    <clipPath id="clip0_123_3189"><rect width="16" height="16" fill="white"/></clipPath>
                </defs>
            </svg>
        </button>
        <a href="#" class="hidden sm:flex hover:opacity-60 transition-opacity duration-200">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.69703 2.253C9.49969 1.45253 10.5872 1.00335 11.7208 1.00412C12.8544 1.0049 13.9413 1.45555 14.7429 2.25712C15.5445 3.05869 15.9951 4.14562 15.9959 5.27921C15.9967 6.41279 15.5475 7.50034 14.747 8.303L8.00003 15.05L1.25303 8.304C0.452554 7.50134 0.00337884 6.41379 0.00415139 5.28021C0.00492393 4.14662 0.455581 3.05969 1.25715 2.25812C2.05871 1.45655 3.14565 1.0059 4.27923 1.00512C5.41282 1.00435 6.50037 1.45353 7.30303 2.254L8.00003 2.948L8.69603 2.252L8.69703 2.253ZM13.687 3.313C13.429 3.05484 13.1227 2.85005 12.7856 2.71033C12.4484 2.5706 12.087 2.49869 11.722 2.49869C11.3571 2.49869 10.9957 2.5706 10.6585 2.71033C10.3213 2.85005 10.015 3.05484 9.75703 3.313L8.00303 5.07L6.24303 3.315C5.71805 2.81403 5.01794 2.53828 4.29233 2.54667C3.56673 2.55507 2.87319 2.84695 2.35994 3.35994C1.8467 3.87292 1.55446 4.56631 1.54569 5.29192C1.53693 6.01752 1.81233 6.71777 2.31303 7.243L8.00003 12.928L13.686 7.242C14.2068 6.72105 14.4993 6.0146 14.4993 5.278C14.4993 4.5414 14.2068 3.83495 13.686 3.314L13.687 3.313Z" fill="black"/>
            </svg>
        </a>
        <a class="cart-page relative inline-flex" href="/panier">
            <svg role="img" aria-hidden="true" focusable="false" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" height="16" width="16">
                <path fill-rule="evenodd" d="M8 0C6.928 0 5.92.298 5.16.75c-.704.42-1.411 1.115-1.411 2V4.5L0 4.501V15h16V4.5h-3.75V2.75c0-.893-.7-1.59-1.41-2.01C10.08.29 9.072 0 8 0Zm2.75 6v3h1.5V6h2.25v7.5h-13V6h2.25v3h1.5V6h5.5Zm0-1.5V2.75c0-.08-.107-.383-.674-.72C9.557 1.724 8.816 1.5 8 1.5c-.808 0-1.55.228-2.07.539-.577.343-.68.648-.68.711V4.5h5.5Z"/>
            </svg>
        </a>
    </div>
</nav>

<!-- Overlay global -->
<div id="overlay" class="fixed inset-0 bg-black/40 z-40 hidden"></div>

<!-- Search Sidebar -->
<div id="search-sidebar" class="fixed top-0 right-0 z-50 flex flex-col w-full h-full bg-white translate-x-full transition-transform duration-300 md:w-105" style="max-height: 100dvh; overflow: hidden">
    <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100 shrink-0">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.82299 10.883C8.71242 11.725 7.32362 12.114 5.93727 11.9713C4.55092 11.8287 3.27042 11.1651 2.35456 10.1146C1.4387 9.06411 0.955777 7.70512 1.0034 6.31227C1.05102 4.91942 1.62563 3.59658 2.6111 2.61111C3.59657 1.62564 4.9194 1.05103 6.31225 1.00341C7.70511 0.955789 9.0641 1.43871 10.1146 2.35457C11.165 3.27043 11.8287 4.55093 11.9713 5.93728C12.1139 7.32363 11.725 8.71243 10.883 9.823L15.603 14.543L14.543 15.603L9.82299 10.883ZM10.5 6.5C10.5 7.56087 10.0786 8.57828 9.32842 9.32843C8.57827 10.0786 7.56086 10.5 6.49999 10.5C5.43912 10.5 4.42171 10.0786 3.67156 9.32843C2.92142 8.57828 2.49999 7.56087 2.49999 6.5C2.49999 5.43914 2.92142 4.42172 3.67156 3.67157C4.42171 2.92143 5.43912 2.5 6.49999 2.5C7.56086 2.5 8.57827 2.92143 9.32842 3.67157C10.0786 4.42172 10.5 5.43914 10.5 6.5Z" fill="#9ca3af"/>
        </svg>
        <input id="search-input" type="text" autocomplete="off" placeholder="Rechercher une série..." class="flex-1 outline-none text-sm text-gray-800 placeholder:text-gray-400"/>
        <button id="search-close" class="text-gray-400 hover:text-gray-900 transition-colors cursor-pointer text-lg leading-none shrink-0 w-8 h-8 flex items-center justify-center">✕</button>
    </div>
    <div id="search-suggestions" class="flex-1 overflow-y-auto px-4 py-4"></div>
</div>

<!-- Modal Compte -->
<!-- ... (identique à ton HTML) ... -->

<!-- Mobile Drawer -->
<div id="mobile-menu" class="fixed inset-0 z-40 flex-col gap-6 px-8 pt-20 pb-8 bg-white hidden md:hidden overflow-y-auto">
    <a href="/celebrities" class="text-sm tracking-widest uppercase border-b border-gray-100 pb-4">Célébrités</a>
    <a href="/series" class="text-sm tracking-widest uppercase border-b border-gray-100 pb-4">Séries</a>
    <a href="/style-finder" class="text-sm tracking-widest uppercase border-b border-gray-100 pb-4">Style Finder</a>
    <a href="#" class="text-sm tracking-widest uppercase border-b border-gray-100 pb-4">Magazine</a>
</div>