<?php
// app/views/auth/reset.php
require_once __DIR__ . '/../partials/header.php';
?>

<main class="auth-feedback-page">
  <div class="auth-feedback-box" style="max-width:380px">

    <?php if (!empty($error)): ?>
      <div class="feedback-icon feedback-icon--err" style="margin:0 auto 1.5rem">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </div>
      <h1 class="feedback-title">Lien expiré<span class="gold-dot">.</span></h1>
      <p class="feedback-sub"><?= htmlspecialchars($error) ?></p>
      <a href="/mot-de-passe-oublie" class="feedback-btn">Faire une nouvelle demande →</a>

    <?php else: ?>
      <p class="forgot-eyebrow">Nouveau mot de passe</p>
      <h1 class="feedback-title">Choisissez<span class="gold-dot">.</span></h1>
      <p class="feedback-sub">Choisissez un mot de passe sécurisé d'au moins 8 caractères.</p>

      <div id="reset-success" style="display:none">
        <div class="feedback-icon feedback-icon--ok" style="margin:0 auto 1rem">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M20 6L9 17l-5-5"/>
          </svg>
        </div>
        <p class="feedback-sub">Mot de passe mis à jour. Vous allez être redirigé…</p>
      </div>

      <form id="form-reset" novalidate>
        <input type="hidden" name="token" id="reset-token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

        <div style="margin-bottom:1.25rem;text-align:left">
          <label for="reset-password" class="auth-label">Nouveau mot de passe</label>
          <div class="pwd-wrap">
            <input id="reset-password" name="password" type="password"
              class="auth-input" placeholder="8 caractères minimum" autocomplete="new-password" required />
            <button type="button" class="pwd-toggle" data-target="reset-password" aria-label="Afficher">
              <svg class="eye-off" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
              <svg class="eye-on" style="display:none" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <p class="auth-error hidden" id="reset-password-error"></p>
        </div>

        <div style="margin-bottom:1.5rem;text-align:left">
          <label for="reset-confirm" class="auth-label">Confirmer</label>
          <input id="reset-confirm" name="confirm" type="password"
            class="auth-input" placeholder="••••••••" autocomplete="new-password" required />
          <p class="auth-error hidden" id="reset-confirm-error"></p>
        </div>

        <p class="auth-error hidden" id="reset-global-error" style="margin-bottom:1rem;text-align:left"></p>

        <button type="submit" class="feedback-btn" id="reset-submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:.6rem">
          <span class="btn-label">Enregistrer</span>
          <span class="btn-arrow">→</span>
          <svg class="spinner" style="display:none;animation:spin .7s linear infinite" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
        </button>
      </form>
    <?php endif; ?>

  </div>
</main>

<style>
.auth-feedback-page { min-height:60vh;display:flex;align-items:center;justify-content:center;padding:3rem 1rem }
.auth-feedback-box  { max-width:400px;width:100%;text-align:center }
.forgot-eyebrow     { font-size:.7rem;letter-spacing:.25em;text-transform:uppercase;color:#B8944B;margin-bottom:.5rem }
.feedback-title     { font-size:1.75rem;font-weight:400;font-family:Georgia,serif;margin-bottom:.5rem;color:#111 }
.gold-dot           { color:#B8944B }
.feedback-sub       { font-size:.9rem;color:#888;line-height:1.6;margin-bottom:1.5rem }
.feedback-icon      { width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center }
.feedback-icon--ok  { background:#f0fdf4;color:#16a34a }
.feedback-icon--err { background:#fef2f2;color:#dc2626 }
.feedback-btn       { display:inline-block;padding:.75rem 2rem;background:#111;color:#fff;text-decoration:none;font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;transition:background .2s;border:none;cursor:pointer;font-family:inherit }
.feedback-btn:hover { background:#B8944B }
.auth-label         { display:block;font-size:.65rem;letter-spacing:.18em;text-transform:uppercase;color:#aaa;margin-bottom:.3rem }
.auth-input         { width:100%;background:transparent;border:none;border-bottom:1px solid rgba(0,0,0,.12);padding:.6rem 0;font-size:.9rem;color:#111;outline:none;font-family:inherit;transition:border-color .2s }
.auth-input:focus   { border-bottom-color:#B8944B }
.auth-error         { font-size:.75rem;color:#ef4444;margin-top:.3rem }
.auth-error.hidden  { display:none }
.pwd-wrap           { position:relative }
.pwd-toggle         { position:absolute;right:0;top:50%;transform:translateY(-50%);background:none;border:none;color:#ccc;cursor:pointer;padding:2px;line-height:1 }
.pwd-toggle:hover   { color:#B8944B }
@keyframes spin { to { transform:rotate(360deg) } }
</style>

<script>
// Toggle password
document.querySelectorAll('.pwd-toggle').forEach(btn => {
  btn.addEventListener('click', () => {
    const input = document.getElementById(btn.dataset.target);
    input.type  = input.type === 'text' ? 'password' : 'text';
    btn.querySelector('.eye-off').style.display = input.type === 'password' ? '' : 'none';
    btn.querySelector('.eye-on').style.display  = input.type === 'password' ? 'none' : '';
  });
});

const form = document.getElementById('form-reset');
if (form) {
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const token    = document.getElementById('reset-token').value;
    const password = document.getElementById('reset-password').value;
    const confirm  = document.getElementById('reset-confirm').value;
    const pwdErr   = document.getElementById('reset-password-error');
    const cfmErr   = document.getElementById('reset-confirm-error');
    const globErr  = document.getElementById('reset-global-error');
    const btn      = document.getElementById('reset-submit');

    [pwdErr, cfmErr, globErr].forEach(el => el.classList.add('hidden'));

    let valid = true;
    if (password.length < 8) {
      pwdErr.textContent = '8 caractères minimum.';
      pwdErr.classList.remove('hidden');
      valid = false;
    }
    if (confirm !== password) {
      cfmErr.textContent = 'Les mots de passe ne correspondent pas.';
      cfmErr.classList.remove('hidden');
      valid = false;
    }
    if (!valid) return;

    btn.disabled = true;
    btn.querySelector('.btn-label').style.opacity = '0';
    btn.querySelector('.btn-arrow').style.display = 'none';
    btn.querySelector('.spinner').style.display   = '';

    try {
      const res  = await fetch('/api/auth/reset', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token, password }),
      });
      const data = await res.json();

      if (data.success) {
        form.style.display = 'none';
        document.querySelector('.forgot-eyebrow').style.display = 'none';
        document.querySelector('.feedback-title').style.display = 'none';
        document.querySelector('.feedback-sub').style.display   = 'none';
        document.getElementById('reset-success').style.display  = '';
        setTimeout(() => window.location.href = data.redirect ?? '/login', 2000);
      } else {
        globErr.textContent = data.message;
        globErr.classList.remove('hidden');
        btn.disabled = false;
        btn.querySelector('.btn-label').style.opacity = '1';
        btn.querySelector('.btn-arrow').style.display = '';
        btn.querySelector('.spinner').style.display   = 'none';
      }
    } catch {
      globErr.textContent = 'Une erreur est survenue.';
      globErr.classList.remove('hidden');
      btn.disabled = false;
    }
  });
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>