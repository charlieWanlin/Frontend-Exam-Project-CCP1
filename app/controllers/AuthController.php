<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../services/Mailer.php';

class AuthController
{
    private User $user;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->user = new User();
    }

    // ────────────────────────────────────────────
    // GET /login
    // ────────────────────────────────────────────
    public function loginPage(): void
    {
        if (!empty($_SESSION['user_id'])) {
            header('Location: /mon-compte');
            exit;
        }
        $tab = 'login';
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // ────────────────────────────────────────────
    // GET /register
    // ────────────────────────────────────────────
    public function registerPage(): void
    {
        if (!empty($_SESSION['user_id'])) {
            header('Location: /mon-compte');
            exit;
        }
        $tab = 'register';
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // ────────────────────────────────────────────
    // POST /api/auth/login  (AJAX JSON)
    // ────────────────────────────────────────────
    public function login(): void
    {
        header('Content-Type: application/json');

        $data     = $this->getJsonBody();
        $email    = trim($data['email']    ?? '');
        $password = $data['password']      ?? '';

        if (!$email || !$password) {
            $this->json(['success' => false, 'message' => 'Tous les champs sont requis.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Adresse e-mail invalide.']);
            return;
        }

        $user = $this->user->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->json(['success' => false, 'message' => 'Email ou mot de passe incorrect.']);
            return;
        }

        // Vérifie que l'email est confirmé
        if (empty($user['email_verified'])) {
            $this->json([
                'success' => false,
                'unverified' => true,
                'message' => 'Veuillez confirmer votre adresse e-mail avant de vous connecter. Vérifiez votre boîte mail.',
            ]);
            return;
        }

        $this->openSession($user);

        $this->json([
            'success'  => true,
            'message'  => 'Bienvenue ' . htmlspecialchars($user['prenom']) . ' !',
            'redirect' => '/',
        ]);
    }

    // ────────────────────────────────────────────
    // POST /api/auth/register  (AJAX JSON)
    // ────────────────────────────────────────────
    public function register(): void
    {
        header('Content-Type: application/json');

        $data     = $this->getJsonBody();
        $prenom   = trim($data['prenom']          ?? '');
        $nom      = trim($data['nom']             ?? '');
        $email    = trim($data['email']           ?? '');
        $password = $data['password']             ?? '';
        $confirm  = $data['password_confirm']     ?? '';

        // ── Validation ──
        $errors = [];

        if (!$prenom) $errors['prenom'] = 'Champ requis.';
        if (!$nom)    $errors['nom']    = 'Champ requis.';

        if (!$email) {
            $errors['email'] = 'Veuillez saisir votre adresse e-mail.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Adresse e-mail invalide.';
        } elseif ($this->user->emailExists($email)) {
            $errors['email'] = 'Un compte existe déjà avec cet e-mail.';
        }

        if (!$password) {
            $errors['password'] = 'Veuillez choisir un mot de passe.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = '8 caractères minimum.';
        }

        if ($password && $confirm !== $password) {
            $errors['password_confirm'] = 'Les mots de passe ne correspondent pas.';
        }

        if ($errors) {
            $this->json(['success' => false, 'errors' => $errors]);
            return;
        }

        // ── Génération du token de vérification ──
        $token = bin2hex(random_bytes(32));

        // ── Création du compte ──
        $userId = $this->user->create([
            'prenom'       => $prenom,
            'nom'          => $nom,
            'email'        => $email,
            'password'     => password_hash($password, PASSWORD_BCRYPT),
            'verify_token' => $token,
        ]);

        if (!$userId) {
            $this->json(['success' => false, 'message' => 'Une erreur est survenue. Veuillez réessayer.']);
            return;
        }

        // ── Envoi du mail de confirmation ──
        $mailSent = Mailer::sendVerification($email, $prenom, $token);

        if (!$mailSent) {
            // Le compte est créé mais le mail a échoué — on le signale sans bloquer
            error_log("[AuthController::register] Mail non envoyé pour user $userId ($email)");
        }

        $this->json([
            'success' => true,
            'message' => 'Compte créé ! Vérifiez votre boîte mail pour confirmer votre adresse e-mail.',
        ]);
    }

   // ────────────────────────────────────────────
// GET /auth/verify?token=xxx
// ────────────────────────────────────────────
public function verify(): void
{
    $token = trim($_GET['token'] ?? '');

    if (!$token) {
        $_SESSION['flash'] = 'Lien de confirmation invalide.';
        header('Location: /login');
        exit;
    }

    $user = $this->user->verifyEmail($token);

    if (!$user) {
        $_SESSION['flash'] = 'Lien invalide ou déjà utilisé.';
        header('Location: /login');
        exit;
    }

    // Connecte directement l'utilisateur après confirmation
    $this->openSession($user);
    $_SESSION['flash'] = 'Adresse e-mail confirmée. Bienvenue !';
    header('Location: /mon-compte');
    exit;
}

    // ────────────────────────────────────────────
    // GET /logout
    // ────────────────────────────────────────────
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        header('Location: /');
        exit;
    }

    // ────────────────────────────────────────────
    // GET /mot-de-passe-oublie
    // ────────────────────────────────────────────
    public function forgotPage(): void
    {
        $title = 'Mot de passe oublié — LE DRESSING';
        require_once __DIR__ . '/../views/auth/forgot.php';
    }

    // ────────────────────────────────────────────
    // POST /api/auth/forgot  (AJAX JSON)
    // ────────────────────────────────────────────
    public function forgot(): void
    {
        header('Content-Type: application/json');

        $data  = $this->getJsonBody();
        $email = trim($data['email'] ?? '');

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Adresse e-mail invalide.']);
            return;
        }

        $user = $this->user->findByEmail($email);

        // Toujours répondre positivement (anti-énumération)
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $this->user->setResetToken($user['id'], $token);
            Mailer::sendPasswordReset($email, $user['prenom'], $token);
        }

        $this->json([
            'success' => true,
            'message' => 'Si un compte correspond à cet e-mail, vous recevrez un lien de réinitialisation.',
        ]);
    }

    // ────────────────────────────────────────────
    // GET /auth/reset?token=xxx
    // ────────────────────────────────────────────
    public function resetPage(): void
    {
        $token = trim($_GET['token'] ?? '');

        if (!$token || !$this->user->findByResetToken($token)) {
            $this->redirectWithMessage('/mot-de-passe-oublie', 'Lien invalide ou expiré.');
            return;
        }

        $title = 'Nouveau mot de passe — LE DRESSING';
        require_once __DIR__ . '/../views/auth/reset.php';
    }

    // ────────────────────────────────────────────
    // POST /api/auth/reset  (AJAX JSON)
    // ────────────────────────────────────────────
    public function reset(): void
    {
        header('Content-Type: application/json');

        $data    = $this->getJsonBody();
        $token   = trim($data['token']            ?? '');
        $new     = $data['password']              ?? '';
        $confirm = $data['password_confirm']      ?? '';

        if (!$token || !$new || !$confirm) {
            $this->json(['success' => false, 'message' => 'Tous les champs sont requis.']);
            return;
        }

        if ($new !== $confirm) {
            $this->json(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
            return;
        }

        if (strlen($new) < 8) {
            $this->json(['success' => false, 'message' => '8 caractères minimum.']);
            return;
        }

        $user = $this->user->findByResetToken($token);

        if (!$user) {
            $this->json(['success' => false, 'message' => 'Lien invalide ou expiré.']);
            return;
        }

        $this->user->updatePassword($user['id'], password_hash($new, PASSWORD_BCRYPT));

        $this->json([
            'success'  => true,
            'message'  => 'Mot de passe mis à jour. Vous pouvez vous connecter.',
            'redirect' => '/login',
        ]);
    }

    // ────────────────────────────────────────────
    // Helpers privés
    // ────────────────────────────────────────────
    private function openSession(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id']     = $user['id'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_nom']    = $user['nom'];
        $_SESSION['user_email']  = $user['email'];
        $_SESSION['user_role']   = $user['role'] ?? 'user';
    }

    private function getJsonBody(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    private function json(array $data): void
    {
        echo json_encode($data);
        exit;
    }

    private function redirectWithMessage(string $url, string $message): void
    {
        $_SESSION['flash'] = $message;
        header('Location: ' . $url);
        exit;
    }
}