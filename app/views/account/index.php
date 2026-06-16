<?php
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
require_once __DIR__ . '/../partials/header.php';
?>

<div class="max-w-[1100px] mx-auto px-8 py-12 grid grid-cols-1 md:grid-cols-[240px_1fr] gap-12 items-start">

  <!-- ── Sidebar ── -->
  <aside class="md:sticky md:top-20">

    <!-- Avatar + nom -->
    <div class="flex flex-col items-center text-center pb-6 mb-5 border-b border-[var(--color-border)]">
      <div class="w-16 h-16 rounded-full bg-[var(--color-dark)] text-[var(--color-cream)] flex items-center justify-center mb-3 overflow-hidden flex-shrink-0" style="font-family:var(--font-heading); font-size:1.4rem">
        <?php if (!empty($currentUser['avatar'])): ?>
          <img src="<?= htmlspecialchars($currentUser['avatar']) ?>" alt="" class="w-full h-full object-cover" />
        <?php else: ?>
          <?= htmlspecialchars(mb_strtoupper(mb_substr($currentUser['prenom'],0,1) . mb_substr($currentUser['nom'],0,1))) ?>
        <?php endif; ?>
      </div>
      <p class="text-sm font-normal text-[var(--color-text)]" style="font-family:var(--font-heading)"><?= htmlspecialchars($currentUser['prenom'] . ' ' . $currentUser['nom']) ?></p>
      <p class="text-[.70rem] text-[var(--color-muted)] mt-0.5"><?= htmlspecialchars($currentUser['email']) ?></p>
    </div>

    <!-- Navigation sidebar -->
    <nav>
      <ul class="flex flex-col gap-0.5">
        <?php
          // Chaque entrée : href => [label, svgPath complet (un seul attribut d), isLogout]
          // ⚠ Le svgPath est passé EN ENTIER dans un seul <path d=""> — pas d'explode
          $navItems = [
            '#section-infos'    => ['Mon profil',     'M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8z', false],
            '#section-securite' => ['Sécurité',       'M3 11h18v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V11zM7 11V7a5 5 0 0 1 10 0v4', false],
            '#section-panier'   => ['Mon panier',     'M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0', false],
            '#section-favoris'  => ['Mes favoris',    'M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l8.84 8.84 8.84-8.84a5.5 5.5 0 0 0 0-7.78z', false],
            '/logout'           => ['Se déconnecter', 'M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9', true],
          ];
          foreach ($navItems as $href => [$label, $svgPath, $isLogout]):
        ?>
          <li>
            <a href="<?= htmlspecialchars($href) ?>"
               class="flex items-center gap-2.5 px-3 py-2.5 rounded-md text-[.70rem] tracking-[.12em] uppercase transition-colors duration-150 no-underline
                 <?= $isLogout
                       ? 'text-[#b5654a] hover:bg-[rgba(181,101,74,.08)]'
                       : 'text-[var(--color-muted)] hover:bg-[var(--color-warm)] hover:text-[var(--color-text)]' ?>
                 <?= (!$isLogout && $href === '#section-infos') ? 'bg-[var(--color-dark)] text-[var(--color-cream)]' : '' ?>
               js-nav-link">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="opacity-50 flex-shrink-0" aria-hidden="true">
                <path d="<?= htmlspecialchars($svgPath) ?>"/>
              </svg>
              <?= htmlspecialchars($label) ?>
            </a>
          </li>
          <?php if ($href === '#section-favoris'): ?>
            <li><div class="h-px bg-[var(--color-border)] my-2"></div></li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </nav>
  </aside>

  <!-- ── Contenu principal ── -->
  <main class="min-w-0">

    <p class="eyebrow mb-1.5">Mon espace</p>
    <h1 class="text-[2.1rem] font-normal mb-10 text-[var(--color-text)]" style="font-family:var(--font-heading)">
      Bonjour, <?= htmlspecialchars($currentUser['prenom']) ?><span class="text-[var(--color-gold)]">.</span>
    </h1>

    <!-- ── Informations personnelles ── -->
    <section class="mb-12" id="section-infos">
      <div class="flex items-baseline justify-between pb-3 mb-5 border-b border-[var(--color-border)]">
        <span class="text-[.70rem] tracking-[.2em] uppercase text-[var(--color-text)]">Informations personnelles</span>
      </div>
      <div id="feedback-profil" class="hidden text-[.72rem] px-4 py-3 mb-4 border"></div>
      <form id="form-profil" class="flex flex-col gap-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="form-input"
                   value="<?= htmlspecialchars($currentUser['prenom']) ?>" required />
          </div>
          <div>
            <label for="nom" class="form-label">Nom</label>
            <input type="text" id="nom" name="nom" class="form-input"
                   value="<?= htmlspecialchars($currentUser['nom']) ?>" required />
          </div>
        </div>
        <div>
          <label for="email" class="form-label">Adresse e-mail</label>
          <input type="email" id="email" name="email" class="form-input"
                 value="<?= htmlspecialchars($currentUser['email']) ?>" required />
        </div>
        <div class="mt-2">
          <button type="submit" class="btn-dark">Enregistrer</button>
        </div>
      </form>
    </section>

    <!-- ── Mot de passe ── -->
    <section class="mb-12" id="section-securite">
      <div class="flex items-baseline justify-between pb-3 mb-5 border-b border-[var(--color-border)]">
        <span class="text-[.70rem] tracking-[.2em] uppercase text-[var(--color-text)]">Mot de passe</span>
      </div>
      <div id="feedback-password" class="hidden text-[.72rem] px-4 py-3 mb-4 border"></div>
      <form id="form-password" class="flex flex-col gap-4">
        <div>
          <label for="current_password" class="form-label">Mot de passe actuel</label>
          <input type="password" id="current_password" name="current_password" class="form-input" required />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="new_password" class="form-label">Nouveau</label>
            <input type="password" id="new_password" name="new_password" class="form-input" required minlength="8" />
          </div>
          <div>
            <label for="confirm_password" class="form-label">Confirmer</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-input" required minlength="8" />
          </div>
        </div>
        <div class="mt-2">
          <button type="submit" class="btn-dark">Mettre à jour</button>
        </div>
      </form>
    </section>

    <!-- ── Panier ── -->
    <section class="mb-12" id="section-panier">
      <div class="pb-3 mb-5 border-b border-[var(--color-border)]">
        <span class="text-[.70rem] tracking-[.2em] uppercase text-[var(--color-text)]">Mon panier</span>
      </div>
      <div class="py-10 px-6 text-center border border-dashed border-[var(--color-border)] bg-[var(--color-warm)]">
        <svg class="w-9 h-9 mx-auto mb-3 opacity-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
          <line x1="3" y1="6" x2="21" y2="6"/>
        </svg>
        <p class="text-sm text-[var(--color-text)] mb-1" style="font-family:var(--font-heading)">Votre panier est vide</p>
        <p class="text-[.72rem] text-[var(--color-muted)] mb-4">Explorez nos looks et trouvez vos prochaines pièces.</p>
        <a href="/celebrities" class="btn-dark inline-flex">Découvrir les looks</a>
      </div>
    </section>

    <!-- ── Favoris ── -->
    <section class="mb-12" id="section-favoris">
      <div class="pb-3 mb-5 border-b border-[var(--color-border)]">
        <span class="text-[.70rem] tracking-[.2em] uppercase text-[var(--color-text)]">Mes favoris</span>
      </div>
      <div class="py-10 px-6 text-center border border-dashed border-[var(--color-border)] bg-[var(--color-warm)]">
        <svg class="w-9 h-9 mx-auto mb-3 opacity-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l8.84 8.84 8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>
        </svg>
        <p class="text-sm text-[var(--color-text)] mb-1" style="font-family:var(--font-heading)">Aucun favori pour l'instant</p>
        <p class="text-[.72rem] text-[var(--color-muted)] mb-4">Enregistrez les looks qui vous inspirent.</p>
        <a href="/celebrities" class="btn-dark inline-flex">Explorer les célébrités</a>
      </div>
    </section>

    <!-- ── Zone de danger ── -->
    <section id="section-danger">
      <div class="pb-3 mb-5 border-b border-[var(--color-border)]">
        <span class="text-[.70rem] tracking-[.2em] uppercase text-[#b5654a]">Zone de danger</span>
      </div>
      <div class="flex items-center justify-between gap-5 p-6 border border-[rgba(181,101,74,.25)]">
        <div>
          <p class="text-sm text-[var(--color-text)] mb-1">Supprimer mon compte</p>
          <p class="text-[.72rem] text-[var(--color-muted)]">Action irréversible. Toutes vos données seront supprimées.</p>
        </div>
        <button id="btn-delete-account"
                class="flex-shrink-0 px-5 py-2.5 text-[.72rem] tracking-[.18em] uppercase cursor-pointer border border-[rgba(181,101,74,.4)] text-[#b5654a] bg-transparent hover:bg-[#b5654a] hover:text-white hover:border-[#b5654a] transition-colors duration-200">
          Supprimer mon compte
        </button>
      </div>
    </section>

  </main>
</div>

<!-- ── Modal suppression ── -->
<div id="modal-delete" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
  <div class="bg-white p-8 max-w-sm w-[90%]">
    <p class="text-xl font-normal mb-2 text-[var(--color-text)]" style="font-family:var(--font-heading)">Confirmer la suppression</p>
    <p class="text-[.75rem] text-[var(--color-muted)] mb-5 leading-relaxed">Entrez votre mot de passe pour confirmer. Cette action est définitive.</p>
    <div id="feedback-delete" class="hidden text-[.72rem] px-4 py-3 mb-4 border"></div>
    <label for="delete_password" class="form-label">Mot de passe</label>
    <input type="password" id="delete_password" class="form-input mb-5" />
    <div class="flex gap-3">
      <button id="btn-confirm-delete"
              class="flex-1 px-4 py-2.5 text-[.72rem] tracking-[.18em] uppercase cursor-pointer border border-[#b5654a] text-[#b5654a] bg-transparent hover:bg-[#b5654a] hover:text-white transition-colors duration-200">
        Supprimer
      </button>
      <button id="btn-cancel-delete"
              class="flex-1 px-4 py-2.5 text-[.72rem] tracking-[.18em] uppercase cursor-pointer border border-[var(--color-border)] text-[var(--color-text)] bg-transparent hover:border-[var(--color-text)] transition-colors duration-200">
        Annuler
      </button>
    </div>
  </div>
</div>

<script>
/* ── Feedback générique ── */
function showFeedback(el, msg, ok) {
  el.textContent = msg;
  el.className = ok
    ? 'text-[.72rem] px-4 py-3 mb-4 border bg-[rgba(74,122,94,.08)] text-[#4a7a5e] border-[rgba(74,122,94,.2)]'
    : 'text-[.72rem] px-4 py-3 mb-4 border bg-[rgba(181,101,74,.08)] text-[#b5654a] border-[rgba(181,101,74,.2)]';
}

/* ── Scroll actif sidebar ── */
const sections = document.querySelectorAll('section[id]');
const navLinks  = document.querySelectorAll('.js-nav-link[href^="#"]');
window.addEventListener('scroll', () => {
  let current = '';
  sections.forEach(s => { if (window.scrollY >= s.offsetTop - 120) current = s.id; });
  navLinks.forEach(a => {
    const active = a.getAttribute('href') === '#' + current;
    a.classList.toggle('bg-[var(--color-dark)]',   active);
    a.classList.toggle('text-[var(--color-cream)]', active);
  });
});

/* ── Formulaire profil ── */
document.getElementById('form-profil').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = this.querySelector('[type=submit]');
  const fb  = document.getElementById('feedback-profil');
  btn.disabled = true;
  try {
    const res  = await fetch('/api/compte/modifier', { method: 'POST', body: new FormData(this) });
    const data = await res.json();
    showFeedback(fb, data.message, data.success);
  } catch {
    showFeedback(fb, 'Une erreur est survenue.', false);
  }
  btn.disabled = false;
});

/* ── Formulaire mot de passe ── */
document.getElementById('form-password').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = this.querySelector('[type=submit]');
  const fb  = document.getElementById('feedback-password');
  btn.disabled = true;
  try {
    const res  = await fetch('/api/compte/mot-de-passe', { method: 'POST', body: new FormData(this) });
    const data = await res.json();
    showFeedback(fb, data.message, data.success);
    if (data.success) this.reset();
  } catch {
    showFeedback(fb, 'Une erreur est survenue.', false);
  }
  btn.disabled = false;
});

/* ── Modal suppression ── */
const modal = document.getElementById('modal-delete');

document.getElementById('btn-delete-account').addEventListener('click', () => {
  modal.style.display = 'flex';
});

document.getElementById('btn-cancel-delete').addEventListener('click', () => {
  modal.style.display = 'none';
  document.getElementById('delete_password').value = '';
  document.getElementById('feedback-delete').className = 'hidden';
});

document.getElementById('btn-confirm-delete').addEventListener('click', async () => {
  const btn  = document.getElementById('btn-confirm-delete');
  const fb   = document.getElementById('feedback-delete');
  const pass = document.getElementById('delete_password').value;

  if (!pass) {
    showFeedback(fb, 'Veuillez entrer votre mot de passe.', false);
    return;
  }

  btn.disabled = true;
  const body = new FormData();
  body.append('password', pass);

  try {
    const res  = await fetch('/api/compte/supprimer', { method: 'POST', body });
    const data = await res.json();
    if (data.success) {
      window.location.href = data.redirect || '/';
      return;
    }
    showFeedback(fb, data.message, false);
  } catch {
    showFeedback(fb, 'Une erreur est survenue.', false);
  }

  btn.disabled = false;
});
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>