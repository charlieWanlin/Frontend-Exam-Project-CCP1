<!-- ── FOOTER ── -->
<footer class="bg-white border-t border-[var(--color-border)] px-4 py-16 md:px-10 xl:px-16">

  <div class="grid grid-cols-2 gap-10 md:grid-cols-5 md:gap-16 mb-12">

    <!-- Colonne marque -->
    <div class="col-span-2 md:col-span-1">
      <p class="font-black text-sm tracking-widest mb-4" style="font-family:var(--font-heading)">LE DRESSING</p>
      <p class="text-xs text-[var(--color-muted)] font-light leading-relaxed">
        Identifiez et achetez les looks de vos célébrités et séries préférées grâce à notre IA.
      </p>
    </div>

    <!-- Colonnes liens -->
    <?php
      $cols = [
        'À Propos'      => ['/about'=>'Qui sommes-nous','/how'=>'Comment ça marche','/tech'=>'Notre technologie','/presse'=>'Presse & Médias','/contact'=>'Contact'],
        'Collections'   => ['/series'=>'Séries TV','/celebrities'=>'Célébrités','/films'=>'Films & Cinéma','/trends'=>'Tendances du moment'],
        'Aide & Support'=> ['/faq'=>'FAQ','/conditions'=>'Conditions d\'utilisation','/confidentialite'=>'Confidentialité','/cookies'=>'Cookies'],
        'Légal'         => ['/mentions-legales'=>'Mentions légales','/cgu'=>'CGU','/cgv'=>'CGV'],
      ];
      foreach ($cols as $heading => $links):
    ?>
      <div>
        <p class="text-xs tracking-widest uppercase font-medium mb-4"><?= $heading ?></p>
        <ul class="flex flex-col gap-2">
          <?php foreach ($links as $href => $label): ?>
            <li><a href="<?= $href ?>" class="text-xs text-[var(--color-muted)] hover:text-black transition-colors duration-200"><?= htmlspecialchars($label) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endforeach; ?>

  </div>

  <!-- Bas de footer : newsletter + réseaux -->
  <div class="flex flex-col gap-6 pt-8 border-t border-[var(--color-border)] md:flex-row md:items-center md:justify-between">

    <!-- Newsletter -->
    <div>
      <p class="text-xs tracking-widest uppercase font-medium mb-2">Restez informés</p>
      <p class="text-xs text-[var(--color-muted)] font-light mb-4 max-w-xs">Recevez les dernières analyses et les looks les plus tendance.</p>
      <div class="flex">
        <input type="email" placeholder="Votre email" class="border border-[var(--color-border)] px-4 py-2.5 text-xs flex-1 outline-none focus:border-black transition-colors duration-200 max-w-60" />
        <button class="bg-black text-white px-5 py-2.5 text-xs tracking-widest hover:opacity-80 transition-opacity whitespace-nowrap">S'INSCRIRE</button>
      </div>
    </div>

    <!-- Réseaux sociaux -->
    <div class="flex flex-col items-start gap-3 md:items-end">
      <p class="text-xs tracking-widest uppercase font-medium">Suivez-nous</p>
      <div class="flex gap-4 items-center">
        <?php
          $socials = [
            'Instagram' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>',
            'X / Twitter' => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
            'TikTok'      => '<path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.28 8.28 0 004.84 1.54V6.78a4.85 4.85 0 01-1.07-.09z"/>',
            'YouTube'     => '<path d="M20.688 7.908a4.83 4.83 0 00-3.396-3.396C15.756 4 12 4 12 4s-3.756 0-5.292.512a4.83 4.83 0 00-3.396 3.396C2.8 9.444 2.8 12 2.8 12s0 2.556.512 4.092a4.83 4.83 0 003.396 3.396C8.244 20 12 20 12 20s3.756 0 5.292-.512a4.83 4.83 0 003.396-3.396C21.2 14.556 21.2 12 21.2 12s0-2.556-.512-4.092zM10.306 14.444V9.556L14.964 12l-4.658 2.444z"/>',
          ];
          foreach ($socials as $name => $path):
        ?>
          <a href="#" aria-label="<?= $name ?>" class="text-[var(--color-muted)] hover:text-black transition-colors duration-200">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><?= $path ?></svg>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</footer>

</body>
</html>