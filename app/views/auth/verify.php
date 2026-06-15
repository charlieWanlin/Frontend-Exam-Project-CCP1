<?php
// app/views/auth/verify.php
require_once __DIR__ . '/../partials/header.php';
?>

<main class="auth-feedback-page">
  <div class="auth-feedback-box">

    <?php if (!empty($success)): ?>
      <div class="feedback-icon feedback-icon--ok">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <path d="M20 6L9 17l-5-5"/>
        </svg>
      </div>
      <h1 class="feedback-title">E-mail confirmé<span class="gold-dot">.</span></h1>
      <p class="feedback-sub"><?= htmlspecialchars($success) ?></p>
      <a href="/login" class="feedback-btn">Se connecter →</a>

    <?php else: ?>
      <div class="feedback-icon feedback-icon--err">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </div>
      <h1 class="feedback-title">Lien invalide<span class="gold-dot">.</span></h1>
      <p class="feedback-sub"><?= htmlspecialchars($error ?? 'Ce lien est invalide ou a déjà été utilisé.') ?></p>
      <a href="/register" class="feedback-btn">Créer un compte →</a>
    <?php endif; ?>

  </div>
</main>

<style>
.auth-feedback-page {
  min-height: 60vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 3rem 1rem;
}
.auth-feedback-box {
  max-width: 400px;
  width: 100%;
  text-align: center;
}
.feedback-icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
}
.feedback-icon--ok  { background: #f0fdf4; color: #16a34a; }
.feedback-icon--err { background: #fef2f2; color: #dc2626; }
.feedback-title {
  font-size: 1.75rem;
  font-weight: 400;
  font-family: Georgia, serif;
  margin-bottom: .5rem;
  color: #111;
}
.gold-dot { color: #B8944B; }
.feedback-sub {
  font-size: .9rem;
  color: #888;
  line-height: 1.6;
  margin-bottom: 2rem;
}
.feedback-btn {
  display: inline-block;
  padding: .75rem 2rem;
  background: #111;
  color: #fff;
  text-decoration: none;
  font-size: .75rem;
  letter-spacing: .2em;
  text-transform: uppercase;
  transition: background .2s;
}
.feedback-btn:hover { background: #B8944B; }
</style>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>