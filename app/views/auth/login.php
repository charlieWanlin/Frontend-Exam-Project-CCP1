<?php
// ============================================================
// GARDE-FOU : si l'utilisateur est déjà connecté, on le renvoie
// directement vers son compte — il n'a rien à faire ici
// ============================================================
if (!empty($_SESSION['user_id'])) {
    header('Location: /mon-compte');
    exit;
}

// ============================================================
// $tab est injectée par le Controller AVANT que cette vue soit
// chargée :
//   - registerPage() → $tab = 'register'
//   - loginPage()    → $tab = 'login'
// Elle sert à deux choses dans le HTML :
//   1. décider quel <title> afficher
//   2. décider quel panneau (login ou register) est visible
// ============================================================
$tab = $tab ?? 'login'; // fallback sécurité si jamais $tab n'est pas définie

// ============================================================
// MESSAGES FLASH
// Messages temporaires stockés en session par le Controller
// (ex: "Email confirmé !" après verify()), lus une seule fois
// puis supprimés immédiatement pour ne pas réapparaître
// ============================================================
$flashError   = $_SESSION['flash_error']   ?? null;
$flashSuccess = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

// ============================================================
// OLD INPUT
// Si le Controller a stocké les anciennes valeurs saisies
// (après une erreur serveur), on les réinjecte dans les champs
// pour que l'utilisateur ne reparte pas d'une page vide
// ============================================================
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

// ============================================================
// TITRE DE PAGE DYNAMIQUE selon l'onglet actif
// ============================================================
$title = $tab === 'register' ? 'Créer un compte — LE DRESSING' : 'Connexion — LE DRESSING';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="/css/output.css" />
</head>
<body style="background:var(--color-dark); font-family:var(--font-sans)">

<div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">

  <!-- ============================================================
       PANNEAU GAUCHE — visuel éditorial (caché sur mobile)
       Photo de fond + logo + citation de marque
       Purement décoratif, aucun rôle fonctionnel
  ============================================================ -->
  <div class="relative hidden md:flex flex-col justify-between p-11 overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center bg-[url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=900&q=85&auto=format&fit=crop')] opacity-40"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-dark)] via-transparent to-transparent"></div>

    <div class="relative z-10 flex items-center gap-3">
      <span class="w-0.5 h-6 bg-[var(--color-gold)]"></span>
      <span class="text-[.62rem] tracking-[.45em] uppercase text-[var(--color-gold)]" style="font-family:var(--font-heading)">Le Dressing</span>
    </div>

    <div class="relative z-10">
      <p class="text-white leading-tight mb-3" style="font-family:var(--font-heading); font-size:clamp(1.6rem,3vw,2.2rem)">
        Le style,<br>c'est une <em class="text-[var(--color-gold-light)] italic">signature</em>.
      </p>
      <p class="text-[.62rem] tracking-[.15em] uppercase text-white/25">Célébrités · Looks · Séries</p>
    </div>
  </div>

  <!-- ============================================================
       PANNEAU DROIT — formulaires fonctionnels
       Contient les deux panneaux login et register.
       Un seul est visible à la fois grâce à la classe 'hidden'
       contrôlée par $tab (PHP) puis par les onglets (liens href)
  ============================================================ -->
  <div class="relative flex items-center justify-center px-8 py-16 bg-[var(--color-cream)]">
    <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-[var(--color-gold)] via-[var(--color-gold-light)] to-[var(--color-gold)]"></div>

    <div class="w-full max-w-sm">

      <!-- ============================================================
           ONGLETS DE NAVIGATION
           Ce sont de simples liens <a href="/login"> et <a href="/register">
           Cliquer recharge la page avec la bonne $tab côté PHP.
           L'onglet actif a une bordure dorée en bas (border-[var(--color-gold)])
           L'onglet inactif a une bordure grise (border-[var(--color-border)])
      ============================================================ -->
      <div class="flex mb-10">
        <?php foreach (['login' => 'Connexion', 'register' => 'Inscription'] as $t => $l): ?>
          <a href="/<?= $t ?>" class="flex-1 text-center pb-3 text-[.72rem] tracking-[.22em] uppercase no-underline border-b-2 transition-colors duration-200
            <?= $tab === $t
              ? 'text-[var(--color-text)] border-[var(--color-gold)]'
              : 'text-[var(--color-muted)] border-[var(--color-border)]' ?>">
            <?= $l ?>
          </a>
        <?php endforeach; ?>
      </div>


      <!-- ============================================================
           PANNEAU CONNEXION
           Visible si $tab === 'login', caché sinon (classe 'hidden')
           Le formulaire a novalidate : c'est auth.js qui valide,
           pas le navigateur. Soumission gérée en AJAX (fetch).
      ============================================================ -->
      <div id="panel-login" class="<?= $tab !== 'login' ? 'hidden' : '' ?>">

        <p class="mb-1" style="font-family:var(--font-heading); font-size:1.7rem; font-weight:400; color:var(--color-text)">
          Bienvenue<span class="text-[var(--color-gold)]">.</span>
        </p>
        <p class="text-[.72rem] text-[var(--color-muted)] mb-8 leading-relaxed">Retrouvez vos favoris et votre espace personnel.</p>

        <!-- Messages flash venant du Controller (erreur ou succès) -->
        <?php if ($tab === 'login' && $flashError): ?>
          <p class="text-xs text-red-600 bg-red-50 border border-red-200 px-3 py-2 mb-5 text-center"><?= htmlspecialchars($flashError) ?></p>
        <?php endif; ?>
        <?php if ($tab === 'login' && $flashSuccess): ?>
          <p class="text-xs text-green-700 bg-green-50 border border-green-200 px-3 py-2 mb-5 text-center"><?= htmlspecialchars($flashSuccess) ?></p>
        <?php endif; ?>

        <!-- Formulaire login — soumis en JSON par auth.js -->
        <form id="form-login" novalidate class="flex flex-col gap-6">
          <div>
            <label for="login-email" class="form-label">E-mail</label>
            <input id="login-email" name="email" type="email" class="form-input"
              autocomplete="email" placeholder="vous@exemple.com"
              value="<?= htmlspecialchars($old['email'] ?? '') ?>" required />
            <!-- Zone d'erreur JS — vide par défaut, remplie par setError() dans auth.js -->
            <p class="form-error hidden" id="login-email-error"></p>
          </div>
          <div>
            <div class="flex justify-between items-center mb-1">
              <label for="login-password" class="form-label" style="margin-bottom:0">Mot de passe</label>
              <a href="/mot-de-passe-oublie" class="text-[.70rem] text-[var(--color-muted)] hover:text-[var(--color-gold)] transition-colors no-underline">Oublié ?</a>
            </div>
            <div class="relative">
              <input id="login-password" name="password" type="password" class="form-input pr-6"
                autocomplete="current-password" placeholder="••••••••" required />
              <!-- Bouton toggle œil — géré par auth.js -->
              <button type="button" class="pwd-toggle absolute right-0 top-1/2 -translate-y-1/2 text-[var(--color-faint)] hover:text-[var(--color-gold)] transition-colors cursor-pointer" data-target="login-password">
                <svg class="eye-off" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                <svg class="eye-on hidden" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <p class="form-error hidden" id="login-password-error"></p>
          </div>
          <!-- Erreur globale (mauvais identifiants, erreur réseau) -->
          <p class="form-error hidden" id="login-global-error"></p>
          <button type="submit" id="login-submit" class="btn-dark w-full justify-center">
            <span class="btn-label">Se connecter</span>
            <span class="btn-arrow transition-transform">→</span>
            <!-- Spinner affiché pendant l'appel fetch — togglé par setLoading() dans auth.js -->
            <svg class="anim-spin hidden w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
          </button>
        </form>

        <p class="text-center mt-5 text-[.72rem] text-[var(--color-muted)]">
          Pas de compte ? <a href="/register" class="text-[var(--color-text)] underline underline-offset-2 hover:text-[var(--color-gold)] transition-colors">Créer un compte</a>
        </p>
      </div>


      <!-- ============================================================
           PANNEAU INSCRIPTION
           Visible si $tab === 'register', caché sinon
           Contient deux états :
             1. #register-form-wrap  → le formulaire (état initial)
             2. #register-success    → l'écran de confirmation (après succès)
           Le basculement entre les deux est géré par auth.js
           après une réponse { success: true } du serveur
      ============================================================ -->
      <div id="panel-register" class="<?= $tab !== 'register' ? 'hidden' : '' ?>">

        <!-- État 2 : succès — caché par défaut, affiché par auth.js après inscription -->
        <div id="register-success" class="hidden text-center py-5">
          <div class="text-4xl mb-4">✓</div>
          <h2 class="mb-2" style="font-family:var(--font-heading); font-size:1.4rem; font-weight:400; color:var(--color-text)">
            Compte créé<span class="text-[var(--color-gold)]">.</span>
          </h2>
          <p class="text-[.75rem] text-[var(--color-muted)] leading-relaxed mb-6">
  Un e-mail de confirmation vient de vous être envoyé.<br>
  Cliquez sur le lien dans cet e-mail pour activer votre compte.
</p>
          <a href="/login" class="text-[.72rem] tracking-[.22em] uppercase text-[var(--color-text)] underline underline-offset-2 hover:text-[var(--color-gold)] transition-colors">Se connecter →</a>
        </div>

        <!-- État 1 : formulaire — visible par défaut -->
        <div id="register-form-wrap">
          <p class="mb-1" style="font-family:var(--font-heading); font-size:1.7rem; font-weight:400; color:var(--color-text)">
            Créer un compte<span class="text-[var(--color-gold)]">.</span>
          </p>
          <p class="text-[.72rem] text-[var(--color-muted)] mb-8 leading-relaxed">Rejoignez des passionnés de mode.</p>

          <?php if ($tab === 'register' && $flashError): ?>
            <p class="text-xs text-red-600 bg-red-50 border border-red-200 px-3 py-2 mb-5 text-center"><?= htmlspecialchars($flashError) ?></p>
          <?php endif; ?>

          <!-- Formulaire register — soumis en JSON par auth.js -->
          <form id="form-register" novalidate class="flex flex-col gap-5">

            <!-- Prénom + Nom côte à côte -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="register-prenom" class="form-label">Prénom</label>
                <input id="register-prenom" name="prenom" type="text" class="form-input"
                  autocomplete="given-name" placeholder="Marie"
                  value="<?= htmlspecialchars($old['prenom'] ?? '') ?>" required />
                <p class="form-error hidden" id="register-prenom-error"></p>
              </div>
              <div>
                <label for="register-nom" class="form-label">Nom</label>
                <input id="register-nom" name="nom" type="text" class="form-input"
                  autocomplete="family-name" placeholder="Dupont"
                  value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required />
                <p class="form-error hidden" id="register-nom-error"></p>
              </div>
            </div>

            <!-- Email -->
            <div>
              <label for="register-email" class="form-label">E-mail</label>
              <input id="register-email" name="email" type="email" class="form-input"
                autocomplete="email" placeholder="vous@exemple.com"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>" required />
              <p class="form-error hidden" id="register-email-error"></p>
            </div>

            <!-- Mot de passe + barre de force -->
            <div>
              <label for="register-password" class="form-label">Mot de passe</label>
              <div class="relative">
                <input id="register-password" name="password" type="password" class="form-input pr-6"
                  autocomplete="new-password" placeholder="8 caractères minimum" required />
                <button type="button" class="pwd-toggle absolute right-0 top-1/2 -translate-y-1/2 text-[var(--color-faint)] hover:text-[var(--color-gold)] transition-colors cursor-pointer" data-target="register-password">
                  <svg class="eye-off" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                  <svg class="eye-on hidden" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              <!-- Barre de force : 4 segments colorés mis à jour en temps réel par auth.js -->
              <div class="flex gap-1 mt-2">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                  <div id="pwd-s<?= $i ?>" class="h-0.5 flex-1 rounded-full bg-[var(--color-border)] transition-colors duration-300"></div>
                <?php endfor; ?>
              </div>
              <p class="form-error hidden" id="register-password-error"></p>
            </div>

            <!-- Confirmation mot de passe -->
            <div>
              <label for="register-password-confirm" class="form-label">Confirmer</label>
              <div class="relative">
                <input id="register-password-confirm" name="password_confirm" type="password" class="form-input pr-6"
                  autocomplete="new-password" placeholder="••••••••" required />
                <button type="button" class="pwd-toggle absolute right-0 top-1/2 -translate-y-1/2 text-[var(--color-faint)] hover:text-[var(--color-gold)] transition-colors cursor-pointer" data-target="register-password-confirm">
                  <svg class="eye-off" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                  <svg class="eye-on hidden" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              <p class="form-error hidden" id="register-password-confirm-error"></p>
            </div>

            <!-- CGU obligatoire -->
            <div class="flex items-start gap-3">
              <input type="checkbox" id="register-cgu" name="cgu"
                class="mt-0.5 w-3 h-3 accent-[var(--color-gold)] flex-shrink-0 cursor-pointer" required />
              <label for="register-cgu" class="text-[.72rem] text-[var(--color-muted)] leading-relaxed cursor-pointer">
                J'accepte les <a href="/conditions" class="text-[var(--color-text)] hover:text-[var(--color-gold)] transition-colors">conditions d'utilisation</a>
                et la <a href="/confidentialite" class="text-[var(--color-text)] hover:text-[var(--color-gold)] transition-colors">politique de confidentialité</a>.
              </label>
            </div>
            <p class="form-error hidden" id="register-cgu-error"></p>

            <!-- Erreur globale serveur -->
            <p class="form-error hidden" id="register-global-error"></p>

            <button type="submit" id="register-submit" class="btn-dark w-full justify-center">
              <span class="btn-label">Créer mon compte</span>
              <span class="btn-arrow">→</span>
              <svg class="anim-spin hidden w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            </button>
          </form>

          <p class="text-center mt-5 text-[.72rem] text-[var(--color-muted)]">
            Déjà un compte ? <a href="/login" class="text-[var(--color-text)] underline underline-offset-2 hover:text-[var(--color-gold)] transition-colors">Se connecter</a>
          </p>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Toast de notification (connexion réussie, etc.) — affiché par showToast() dans auth.js -->
<div id="auth-toast"
  class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-[var(--color-dark)] text-white px-6 py-3 text-[.72rem] tracking-[.2em] uppercase opacity-0 pointer-events-none transition-opacity duration-300"
  role="status" aria-live="polite">
</div>

<!-- JS externalisé — tout le comportement interactif est dans auth.js -->
<script src="/js/auth.js"></script>

</body>
</html>