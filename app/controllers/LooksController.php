<?php
// app/controllers/LooksController.php

class LooksController
{
    // ── GET /looks ────────────────────────────────────────────────
    public function index(): void
    {
        require_once __DIR__ . '/../models/LookModel.php';
        $model      = new LookModel();
        $categories = $model->getCategories();
        $series     = $model->getSeries();
        $title      = 'Looks & Style — LE DRESSING';

        require_once __DIR__ . '/../views/looks/index.php';
    }

    // ── GET /api/looks  (JSON — grille paginée) ───────────────────
    public function api(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $categorie = strtolower(trim($_GET['categorie'] ?? 'tous'));
        $sort      = trim($_GET['sort']     ?? 'recents');
        $page      = max(1, (int)($_GET['page']     ?? 1));
        $perPage   = min(48, max(1, (int)($_GET['per_page'] ?? 20)));

        $sortsOk = ['recents', 'popularite', 'alpha-asc'];
        if (!in_array($sort, $sortsOk, true)) $sort = 'recents';

        require_once __DIR__ . '/../models/LookModel.php';
        $model = new LookModel();

        $items   = $model->getFiltered($categorie, $sort, $page, $perPage);
        $total   = $model->countFiltered($categorie);
        $hasMore = ($page * $perPage) < $total;

        echo json_encode([
            'items'    => $items,
            'total'    => $total,
            'has_more' => $hasMore,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ── GET /looks/{id}  (page détail) ────────────────────────────
    public function show(int $id): void
    {
        require_once __DIR__ . '/../models/LookModel.php';
        $model = new LookModel();
        $look  = $model->getById($id);

        if (!$look) {
            http_response_code(404);
            $title = '404 — Look introuvable';
            require_once __DIR__ . '/../views/errors/404.php';
            return;
        }

        $vetements = $model->getVetements($look['id']);
        $related   = $model->getRelated($look['id'], $look['categorie'] ?? null, 4);
        $title     = ($look['titre'] ?? 'Look') . ' — LE DRESSING';

        require_once __DIR__ . '/../views/looks/show.php';
    }
}