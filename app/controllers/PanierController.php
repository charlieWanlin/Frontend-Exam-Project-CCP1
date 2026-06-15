<?php

class PanierController
{
    private Panier $panierModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Panier.php';
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        $this->panierModel = new Panier();
    }

    // ── Identifiants courants (user ou session) ──────────────────────
    private function getIds(): array
    {
        $userId    = AuthMiddleware::isLoggedIn() ? (int) $_SESSION['user_id'] : null;
        $sessionId = session_id();
        return [$userId, $sessionId];
    }

    // ── GET /panier ──────────────────────────────────────────────────
    public function index(): void
    {
        [$userId, $sessionId] = $this->getIds();

        $items     = $this->panierModel->getItems($userId, $sessionId);
        $sousTotal = $this->panierModel->sousTotal($userId, $sessionId);
        $livraison = $sousTotal > 0 && $sousTotal >= 80 ? 0 : 5.90;
        $total     = $sousTotal > 0 ? $sousTotal + $livraison : 0;
        $count     = $this->panierModel->countItems($userId, $sessionId);

        $flash = $_SESSION['flash_panier'] ?? null;
        unset($_SESSION['flash_panier']);

        require_once __DIR__ . '/../views/panier/index.php';
    }

    // ── GET /api/panier  (JSON — badge navbar + mini-panier) ─────────
    public function api(): void
    {
        [$userId, $sessionId] = $this->getIds();

        $items     = $this->panierModel->getItems($userId, $sessionId);
        $sousTotal = $this->panierModel->sousTotal($userId, $sessionId);
        $count     = $this->panierModel->countItems($userId, $sessionId);
        $livraison = $sousTotal > 0 && $sousTotal >= 80 ? 0 : 5.90;

        header('Content-Type: application/json');
        echo json_encode([
            'count'     => $count,
            'sousTotal' => $sousTotal,
            'livraison' => $sousTotal > 0 ? $livraison : 0,
            'total'     => $sousTotal > 0 ? $sousTotal + $livraison : 0,
            'items'     => array_map(fn($item) => [
                'id'       => $item['id'],
                'look_id'  => $item['look_id'],
                'nom'      => $item['nom'],
                'image'    => $item['image'],
                'prix'     => (float) $item['prix'],
                'taille'   => $item['taille'],
                'quantite' => (int) $item['quantite'],
            ], $items),
        ]);
    }

    // ── POST /api/panier/ajouter ─────────────────────────────────────
    public function ajouter(): void
    {
        [$userId, $sessionId] = $this->getIds();

        $lookId = (int)   ($_POST['look_id'] ?? 0);
        $nom    = trim(   $_POST['nom']      ?? '');
        $prix   = (float) ($_POST['prix']    ?? 0);
        $image  = trim(   $_POST['image']    ?? '');
        $taille = trim(   $_POST['taille']   ?? '');

        if ($lookId && $nom) {
            $this->panierModel->addItem($lookId, $nom, $prix, $image, $taille, $userId, $sessionId);
            $_SESSION['flash_panier'] = 'Article ajouté au panier.';
        }

        // Réponse JSON si requête AJAX, redirect sinon
        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'count' => $this->panierModel->countItems($userId, $sessionId)]);
            return;
        }

        $redirect = $_POST['redirect'] ?? $_SERVER['HTTP_REFERER'] ?? '/panier';
        header("Location: {$redirect}");
        exit;
    }

    // ── POST /api/panier/modifier ────────────────────────────────────
    public function modifier(): void
    {
        [$userId, $sessionId] = $this->getIds();

        $itemId   = (int) ($_POST['item_id']  ?? 0);
        $quantite = (int) ($_POST['quantite'] ?? 0);

        if ($itemId) {
            $this->panierModel->updateQuantite($itemId, $quantite, $userId, $sessionId);
        }

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            $sousTotal = $this->panierModel->sousTotal($userId, $sessionId);
            $livraison = $sousTotal > 0 && $sousTotal >= 80 ? 0 : 5.90;
            echo json_encode([
                'success'   => true,
                'count'     => $this->panierModel->countItems($userId, $sessionId),
                'sousTotal' => $sousTotal,
                'livraison' => $sousTotal > 0 ? $livraison : 0,
                'total'     => $sousTotal > 0 ? $sousTotal + $livraison : 0,
            ]);
            return;
        }

        header('Location: /panier');
        exit;
    }

    // ── POST /api/panier/retirer ─────────────────────────────────────
    public function retirer(): void
    {
        [$userId, $sessionId] = $this->getIds();

        $itemId = (int) ($_POST['item_id'] ?? 0);

        if ($itemId) {
            $this->panierModel->removeItem($itemId, $userId, $sessionId);
        }

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            $sousTotal = $this->panierModel->sousTotal($userId, $sessionId);
            $livraison = $sousTotal > 0 && $sousTotal >= 80 ? 0 : 5.90;
            echo json_encode([
                'success'   => true,
                'count'     => $this->panierModel->countItems($userId, $sessionId),
                'sousTotal' => $sousTotal,
                'livraison' => $sousTotal > 0 ? $livraison : 0,
                'total'     => $sousTotal > 0 ? $sousTotal + $livraison : 0,
            ]);
            return;
        }

        header('Location: /panier');
        exit;
    }

    // ── POST /api/panier/vider ───────────────────────────────────────
    public function vider(): void
    {
        [$userId, $sessionId] = $this->getIds();
        $this->panierModel->clear($userId, $sessionId);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'count' => 0]);
            return;
        }

        header('Location: /panier');
        exit;
    }

    // ── Helper ───────────────────────────────────────────────────────
    private function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest'
            || str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    }
}