<?php require_once __DIR__ . '/../partials/header.php'; ?>

<main class="max-w-6xl mx-auto px-4 py-10 md:px-6 md:py-14">

  <h1 class="text-2xl md:text-3xl mb-8 text-[var(--color-text)]" style="font-family:var(--font-heading)">
    Mon Panier
    <span class="text-[var(--color-muted)] font-normal text-lg">(<?= $count ?> article<?= $count>1?'s':'' ?>)</span>
  </h1>

  <!-- Flash message -->
  <?php if ($flash): ?>
    <div id="js-flash" class="mb-6 border border-[var(--color-border)] bg-[var(--color-warm)] text-[var(--color-muted)] text-xs tracking-widest uppercase px-4 py-3 transition-opacity duration-500">
      <?= htmlspecialchars($flash) ?>
    </div>
    <script>setTimeout(() => { const f=document.getElementById('js-flash'); if(f) f.style.opacity='0'; }, 3000);</script>
  <?php endif; ?>

  <!-- Panier vide -->
  <?php if (empty($items)): ?>
    <div class="text-center py-24">
      <p class="text-[var(--color-muted)] text-sm mb-8">Votre panier est vide.</p>
      <a href="/looks" class="btn-dark inline-flex">Découvrir les looks</a>
    </div>

  <?php else: ?>
    <div class="lg:grid lg:grid-cols-3 lg:gap-12">

      <!-- ── Liste articles ── -->
      <div class="lg:col-span-2">
        <ul class="flex flex-col divide-y divide-[var(--color-border)]" id="js-panier-liste">

          <?php foreach ($items as $item): ?>
            <li class="flex gap-4 py-6 items-start js-panier-item" data-item-id="<?= $item['id'] ?>">

              <!-- Image -->
              <div class="w-20 h-24 flex-shrink-0 bg-[var(--color-warm)] overflow-hidden">
                <?php if ($item['image']): ?>
                  <img src="/assets/img/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['nom']) ?>" class="w-full h-full object-cover" loading="lazy" />
                <?php endif; ?>
              </div>

              <!-- Infos -->
              <div class="flex-1 flex flex-col gap-2 min-w-0">
                <p class="text-sm font-medium leading-snug truncate text-[var(--color-text)]"><?= htmlspecialchars($item['nom']) ?></p>
                <?php if ($item['taille']): ?><p class="text-xs text-[var(--color-muted)] tracking-widest uppercase">Taille : <?= htmlspecialchars($item['taille']) ?></p><?php endif; ?>
                <p class="text-sm text-[var(--color-text)]"><?= number_format($item['prix'],2,',',' ') ?> €</p>

                <!-- Contrôle quantité -->
                <div class="flex items-center gap-2 mt-1">
                  <?php foreach (['-'=>'minus','+'=>'plus'] as $sym => $action): ?>
                    <button type="button" class="js-qty-btn w-7 h-7 border border-[var(--color-border)] text-[var(--color-muted)] hover:border-[var(--color-text)] flex items-center justify-center text-sm transition-colors" data-action="<?= $action ?>" aria-label="<?= $action==='minus'?'Diminuer':'Augmenter' ?>"><?= $sym ?></button>
                  <?php endforeach; ?>
                  <input type="number" class="js-qty-input w-10 text-center border border-[var(--color-border)] text-sm py-1 outline-none focus:border-[var(--color-text)] transition-colors" value="<?= (int)$item['quantite'] ?>" min="0" max="99" aria-label="Quantité" />
                </div>
              </div>

              <!-- Sous-total + suppression -->
              <div class="flex flex-col items-end gap-3 flex-shrink-0">
                <p class="text-sm font-medium js-item-subtotal text-[var(--color-text)]"><?= number_format($item['prix']*$item['quantite'],2,',',' ') ?> €</p>
                <button type="button" class="js-remove-btn text-[var(--color-faint)] hover:text-[var(--color-text)] transition-colors" aria-label="Retirer l'article">
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1 1l12 12M13 1L1 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
              </div>

            </li>
          <?php endforeach; ?>

        </ul>

        <div class="mt-4 flex justify-end">
          <button type="button" id="js-vider-btn" class="text-xs text-[var(--color-muted)] hover:text-[var(--color-text)] tracking-widest uppercase underline underline-offset-4 transition-colors cursor-pointer">
            Vider le panier
          </button>
        </div>
      </div>

      <!-- ── Récapitulatif ── -->
      <aside class="mt-10 lg:mt-0">
        <div class="border border-[var(--color-border)] p-6 md:p-8 sticky top-20">

          <p class="text-[.70rem] tracking-widest uppercase text-[var(--color-muted)] mb-1">Sous-total</p>
          <p id="js-sous-total" class="text-lg font-medium mb-4 text-[var(--color-text)]"><?= number_format($sousTotal,2,',',' ') ?> €</p>

          <p class="text-[.70rem] tracking-widest uppercase text-[var(--color-muted)] mb-1">Livraison</p>
          <p id="js-livraison" class="text-sm text-[var(--color-muted)] mb-1"><?= $livraison===0.0?'Gratuite':number_format($livraison,2,',',' ').' €' ?></p>
          <p id="js-livraison-hint" class="text-xs text-[var(--color-faint)] mb-4 <?= $sousTotal>=80?'hidden':'' ?>">
            <?php if ($sousTotal < 80): ?>Plus que <?= number_format(80-$sousTotal,2,',',' ') ?> € pour la livraison offerte.<?php endif; ?>
          </p>

          <p class="text-[.70rem] tracking-widest uppercase text-[var(--color-muted)] mb-1">Total TTC</p>
          <p id="js-total" class="text-xl font-bold mb-6 text-[var(--color-text)]"><?= number_format($total,2,',',' ') ?> €</p>

          <a href="/checkout" class="btn-dark w-full justify-center mb-2">Commander</a>
          <p class="text-xs text-[var(--color-faint)] text-center">Paiement sécurisé</p>

          <!-- Logos paiement -->
          <div class="flex items-center justify-center gap-2 mt-3">
            <!-- Visa -->
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="24" viewBox="0 0 38 24" role="img" aria-label="Visa"><rect width="38" height="24" rx="4" fill="#1A1F71"/><path d="M16.5 16.5H14.1L15.6 7.5H18L16.5 16.5ZM12.2 7.5L9.9 13.6L9.6 12.1L8.8 8.3C8.8 8.3 8.6 7.5 7.7 7.5H4V7.7C4 7.7 5.2 8 6.5 8.8L8.6 16.5H11.1L15 7.5H12.2ZM30.1 16.5H32.3L30.4 7.5H28.4C27.6 7.5 27.4 8.1 27.4 8.1L23.8 16.5H26.3L26.8 15.1H29.8L30.1 16.5ZM27.5 13.2L28.7 9.9L29.4 13.2H27.5ZM23.4 9.9L23.7 8.1C23.7 8.1 22.6 7.7 21.4 7.7C20.1 7.7 17 8.3 17 11C17 13.5 20.5 13.6 20.5 14.8C20.5 16 17.4 15.8 16.4 14.9L16.1 16.8C16.1 16.8 17.2 17.3 18.9 17.3C20.6 17.3 23.5 16.4 23.5 13.9C23.5 11.3 20 11.1 20 10.1C20 9.1 22.5 9.2 23.4 9.9Z" fill="white"/></svg>
            <!-- Mastercard -->
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="24" viewBox="0 0 38 24" role="img" aria-label="Mastercard"><rect width="38" height="24" rx="4" fill="#252525"/><circle cx="15" cy="12" r="7" fill="#EB001B"/><circle cx="23" cy="12" r="7" fill="#F79E1B"/><path d="M19 6.8A7 7 0 0 1 22.2 12 7 7 0 0 1 19 17.2 7 7 0 0 1 15.8 12 7 7 0 0 1 19 6.8Z" fill="#FF5F00"/></svg>
            <!-- PayPal -->
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="24" viewBox="0 0 38 24" role="img" aria-label="PayPal"><rect width="38" height="24" rx="4" fill="#F5F7FA" stroke="#E8E8E8" stroke-width="0.5"/><path d="M16.5 7H13L11 17H13.3L13.8 14H15.5C17.8 14 19.5 12.8 19.9 10.8C20.3 9 19.2 7 16.5 7ZM15.7 12H14.2L14.7 9H16.2C17.3 9 17.8 9.7 17.6 10.8C17.4 11.6 16.7 12 15.7 12Z" fill="#003087"/><path d="M21.5 7H18L16 17H18.3L18.8 14H20.5C22.8 14 24.5 12.8 24.9 10.8C25.3 9 24.2 7 21.5 7ZM20.7 12H19.2L19.7 9H21.2C22.3 9 22.8 9.7 22.6 10.8C22.4 11.6 21.7 12 20.7 12Z" fill="#009CDE"/></svg>
          </div>

          <!-- Code promo -->
          <div class="mt-6 pt-6 border-t border-[var(--color-border)]">
            <label class="text-[.70rem] tracking-widest uppercase text-[var(--color-muted)] block mb-2">Code promo</label>
            <div class="flex gap-2">
              <input type="text" id="js-code-promo" placeholder="Code" class="border border-[var(--color-border)] px-4 py-2.5 text-sm flex-1 outline-none focus:border-[var(--color-text)] transition-colors" />
              <button id="js-appliquer-promo" class="border border-[var(--color-text)] text-[var(--color-text)] text-xs tracking-widest uppercase px-4 py-2.5 hover:bg-[var(--color-text)] hover:text-white transition-all cursor-pointer">Appliquer</button>
            </div>
            <p id="js-promo-msg" class="hidden text-xs text-[var(--color-muted)] mt-2"></p>
          </div>

        </div>
      </aside>

    </div>
  <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

<script>
(function () {
  const fmt = n => parseFloat(n).toFixed(2).replace('.',',') + ' €';

  async function apiPost(url, body) {
    const fd = new FormData();
    Object.entries(body).forEach(([k,v]) => fd.append(k,v));
    const r = await fetch(url, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body:fd });
    return r.json();
  }

  function updateRecap(data) {
    const set = (id,val) => { const el=document.getElementById(id); if(el) el.textContent=val; };
    set('js-sous-total', fmt(data.sousTotal));
    set('js-total', fmt(data.total));
    const liv = document.getElementById('js-livraison');
    if (liv) liv.textContent = data.livraison===0 ? 'Gratuite' : fmt(data.livraison);
    const hint = document.getElementById('js-livraison-hint');
    if (hint) {
      if (data.sousTotal >= 80) { hint.classList.add('hidden'); }
      else { hint.textContent = `Plus que ${(80-data.sousTotal).toFixed(2).replace('.',',')} € pour la livraison offerte.`; hint.classList.remove('hidden'); }
    }
    document.querySelectorAll('[data-panier-count]').forEach(el => { el.textContent=data.count; el.hidden=data.count===0; });
  }

  function checkEmpty() { if (!document.querySelector('.js-panier-item')) window.location.reload(); }

  /* Quantité ± */
  document.getElementById('js-panier-liste')?.addEventListener('click', async e => {
    const btn = e.target.closest('.js-qty-btn'); if (!btn) return;
    const li = btn.closest('.js-panier-item');
    const input = li.querySelector('.js-qty-input');
    const prix = parseFloat(li.querySelector('.js-item-subtotal')?.textContent.replace(',','.')) || 0;
    let qty = parseInt(input.value,10) || 0;
    qty += btn.dataset.action==='plus' ? 1 : -1;
    qty = Math.max(0,qty); input.value = qty;
    const data = await apiPost('/api/panier/modifier', { item_id: li.dataset.itemId, quantite: qty });
    if (qty===0) { li.remove(); checkEmpty(); } else { const sub=li.querySelector('.js-item-subtotal'); if(sub) sub.textContent=fmt(prix/li.querySelector('.js-qty-input').defaultValue*qty); }
    updateRecap(data);
  });

  /* Retirer */
  document.getElementById('js-panier-liste')?.addEventListener('click', async e => {
    const btn = e.target.closest('.js-remove-btn'); if (!btn) return;
    const li = btn.closest('.js-panier-item');
    li.style.opacity = '0.4';
    const data = await apiPost('/api/panier/retirer', { item_id: li.dataset.itemId });
    li.remove(); checkEmpty(); updateRecap(data);
  });

  /* Vider */
  document.getElementById('js-vider-btn')?.addEventListener('click', async () => {
    if (!confirm('Vider tout le panier ?')) return;
    await apiPost('/api/panier/vider', {});
    window.location.reload();
  });
})();
</script>