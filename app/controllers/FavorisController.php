<?php

class FavorisController
{
    private Favoris $favorisModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Favoris.php';
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $this->favorisModel = new Favoris();
    }

    // ── GET /favoris ─────────────────────────────────────────────────
    public function index(): void
    {
        AuthMiddleware::check(); // redirige vers /login si pas connecté

        $userId  = (int) $_SESSION['user_id'];
        $items   = $this->favorisModel->getAll($userId);
        $count   = count($items);

        $flash   = $_SESSION['flash_favoris'] ?? null;
        unset($_SESSION['flash_favoris']);

        require_once __DIR__ . '/../views/favoris/index.php';
    }

    // ── POST /favoris/toggle ─────────────────────────────────────────
    public function toggle(): void
    {
        // Si pas connecté → rediriger vers login avec message
        if (!AuthMiddleware::isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_POST['redirect'] ?? $_SERVER['HTTP_REFERER'] ?? '/';
            $_SESSION['flash_success']        = 'Connectez-vous pour sauvegarder vos favoris.';
            header('Location: /login');
            exit;
        }

        $userId = (int) $_SESSION['user_id'];
        $lookId = (int) ($_POST['look_id'] ?? 0);
        $nom    = trim($_POST['nom']   ?? '');
        $image  = trim($_POST['image'] ?? '');
        $prix   = (float) ($_POST['prix'] ?? 0);
        $slug   = trim($_POST['slug']  ?? '');

        if ($lookId && $nom) {
            $added = $this->favorisModel->toggle($userId, $lookId, $nom, $image, $prix, $slug);
            $_SESSION['flash_favoris'] = $added
                ? 'Ajouté à vos favoris.'
                : 'Retiré de vos favoris.';
        }

        $redirect = $_POST['redirect'] ?? $_SERVER['HTTP_REFERER'] ?? '/favoris';
        header("Location: {$redirect}");
        exit;
    }

    // ── POST /favoris/supprimer ──────────────────────────────────────
    public function supprimer(): void
    {
        AuthMiddleware::check();

        $userId = (int) $_SESSION['user_id'];
        $lookId = (int) ($_POST['look_id'] ?? 0);

        if ($lookId) {
            $this->favorisModel->remove($userId, $lookId);
        }

        header('Location: /favoris');
        exit;
    }
}