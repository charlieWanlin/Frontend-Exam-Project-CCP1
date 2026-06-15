<?php

class AuthMiddleware
{
    // ── Vérifier que l'utilisateur est connecté ──────────────────────
    public static function check(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            // Mémoriser l'URL demandée pour rediriger après login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /login');
            exit;
        }
    }

    // ── Vérifier que l'utilisateur est connecté (sans redirection) ───
    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return !empty($_SESSION['user_id']);
    }

    // ── Récupérer l'utilisateur connecté ────────────────────────────
    public static function user(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id'     => $_SESSION['user_id'],
            'prenom' => $_SESSION['user_prenom'] ?? '',
            'nom'    => $_SESSION['user_nom']    ?? '',
            'email'  => $_SESSION['user_email']  ?? '',
        ];
    }

    // ── Connecter un utilisateur (appelé après vérif password) ───────
    public static function login(array $user): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Regénérer l'ID de session (protection fixation)
        $oldSessionId = session_id();
        session_regenerate_id(true);

        $_SESSION['user_id']     = $user['id'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_nom']    = $user['nom'];
        $_SESSION['user_email']  = $user['email'];

        // Merge panier guest → user
        require_once __DIR__ . '/../models/Panier.php';
        $panier = new Panier();
        $panier->mergeSession($oldSessionId, (int) $user['id']);
    }

    // ── Déconnecter ──────────────────────────────────────────────────
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    // ── Rediriger les utilisateurs déjà connectés (pages login/register) ─
    public static function redirectIfLoggedIn(string $to = '/'): void
    {
        if (self::isLoggedIn()) {
            header("Location: {$to}");
            exit;
        }
    }
}