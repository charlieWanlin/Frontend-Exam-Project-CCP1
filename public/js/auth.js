(() => {
  'use strict';

  // Toggle visibilité mot de passe
  document.querySelectorAll('.pwd-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const input  = document.getElementById(btn.dataset.target);
      const isText = input.type === 'text';

      input.type = isText ? 'password' : 'text';

      // ✅ On manipule classList.toggle au lieu de style.display
      btn.querySelector('.eye-off').classList.toggle('hidden', !isText);
      btn.querySelector('.eye-on').classList.toggle('hidden', isText);
      btn.setAttribute('aria-label', isText ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
    });
  });


  // Barre de force du mot de passe
  const pwdInput = document.getElementById('register-password');
  if (pwdInput) {
    pwdInput.addEventListener('input', () => {
      const v = pwdInput.value;
      let score = 0;

      if (v.length >= 8)          score++;
      if (/[A-Z]/.test(v))        score++;
      if (/[0-9]/.test(v))        score++;
      if (/[^A-Za-z0-9]/.test(v)) score++;

      const colors = ['', '#ef4444', '#f97316', '#eab308', '#B8944B'];

      for (let i = 1; i <= 4; i++) {
        const seg = document.getElementById('pwd-s' + i);
        if (seg) seg.style.background = i <= score ? colors[score] : 'rgba(0,0,0,0.08)';
      }
    });
  }


  // Helpers partagés

  function setError(id, msg) {
    const el = document.getElementById(id);
    if (!el) return;
    el.textContent = msg;
    el.classList.toggle('hidden', !msg);

    const input = document.getElementById(id.replace('-error', ''));
    if (input) input.classList.toggle('invalid', !!msg);
  }

  function clearErrors(prefix) {
    document.querySelectorAll(`[id^="${prefix}"][id$="-error"]`).forEach(el => {
      el.textContent = '';
      el.classList.add('hidden');
    });
    document.querySelectorAll('.auth-input.invalid').forEach(i => i.classList.remove('invalid'));
  }

  function setLoading(btnId, loading) {
    const btn = document.getElementById(btnId);
    if (!btn) return;
    btn.disabled = loading;
    btn.querySelector('.btn-label').style.opacity = loading ? '0' : '1';
    btn.querySelector('.btn-arrow').style.display = loading ? 'none' : '';
    // ✅ Corrigé : '.anim-spin' au lieu de '.spinner'
    btn.querySelector('.anim-spin').style.display = loading ? '' : 'none';
  }

  function showToast(msg) {
    const t = document.getElementById('auth-toast');
    if (!t) return;
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
  }


  // Formulaire de connexion
  const formLogin = document.getElementById('form-login');
  if (formLogin) {
    formLogin.addEventListener('submit', async (e) => {
      e.preventDefault();
      clearErrors('login');

      const email    = document.getElementById('login-email').value.trim();
      const password = document.getElementById('login-password').value;
      let valid = true;

      if (!email) {
        setError('login-email-error', 'Adresse e-mail requise.'); valid = false;
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        setError('login-email-error', 'Adresse e-mail invalide.'); valid = false;
      }
      if (!password) {
        setError('login-password-error', 'Mot de passe requis.'); valid = false;
      }
      if (!valid) return;

      setLoading('login-submit', true);

      try {
        const res  = await fetch('/api/auth/login', {
          method : 'POST',
          headers: {
            'Content-Type'    : 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify({ email, password }),
        });
        const data = await res.json();

        if (data.success) {
          showToast(data.message ?? 'Connexion réussie');
          setTimeout(() => window.location.href = data.redirect ?? '/mon-compte', 600);
        } else {
          if (data.unverified) {
            const alert = document.getElementById('login-unverified-alert');
            if (alert) alert.style.display = '';
          } else {
            setError('login-global-error', data.message ?? 'Identifiants incorrects.');
          }
          setLoading('login-submit', false);
        }
      } catch {
        setError('login-global-error', 'Une erreur est survenue. Réessayez.');
        setLoading('login-submit', false);
      }
    });
  }


  // Formulaire d'inscription
  const formReg = document.getElementById('form-register');
  if (formReg) {
    formReg.addEventListener('submit', async (e) => {
      e.preventDefault();
      clearErrors('register');

      const prenom   = document.getElementById('register-prenom').value.trim();
      const nom      = document.getElementById('register-nom').value.trim();
      const email    = document.getElementById('register-email').value.trim();
      const password = document.getElementById('register-password').value;
      const confirm  = document.getElementById('register-password-confirm').value;
      const cgu      = document.getElementById('register-cgu').checked;
      let valid = true;

      if (!prenom) { setError('register-prenom-error', 'Champ requis.'); valid = false; }
      if (!nom)    { setError('register-nom-error',    'Champ requis.'); valid = false; }

      if (!email) {
        setError('register-email-error', 'Adresse e-mail requise.'); valid = false;
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        setError('register-email-error', 'Adresse e-mail invalide.'); valid = false;
      }

      if (!password) {
        setError('register-password-error', 'Mot de passe requis.'); valid = false;
      } else if (password.length < 8) {
        setError('register-password-error', '8 caractères minimum.'); valid = false;
      }

      if (password && confirm !== password) {
        setError('register-password-confirm-error', 'Les mots de passe ne correspondent pas.'); valid = false;
      }

      if (!cgu) {
        setError('register-cgu-error', 'Vous devez accepter les conditions.'); valid = false;
      }

      if (!valid) return;

      setLoading('register-submit', true);

      try {
        const res  = await fetch('/api/auth/register', {
          method : 'POST',
          headers: {
            'Content-Type'    : 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify({
            prenom,
            nom,
            email,
            password,
            password_confirm: confirm,
            cgu: true,
          }),
        });
        const data = await res.json();

        if (data.success) {
          document.getElementById('register-form-wrap').style.display = 'none';
          document.getElementById('register-success').classList.remove('hidden');
        } else {
          if (data.errors) {
            Object.entries(data.errors).forEach(([field, msg]) =>
              setError('register-' + field + '-error', msg)
            );
          }
          setError('register-global-error', data.message ?? 'Une erreur est survenue.');
          setLoading('register-submit', false);
        }
      } catch {
        setError('register-global-error', 'Une erreur est survenue. Réessayez.');
        setLoading('register-submit', false);
      }
    });
  }

})();