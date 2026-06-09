<?php

class SeriesController {

    public function index(): void {
        $title = 'Séries — LE DRESSING';

        require_once __DIR__ . '/../models/SerieModel.php';
        $heroImages = (new SerieModel())->getForHero(24);

        $col1 = array_slice($heroImages, 0,  8);
        $col2 = array_slice($heroImages, 8,  8);
        $col3 = array_slice($heroImages, 16, 8);

        require_once __DIR__ . '/../views/series/index.php';
    }

    public function api(): void {
        ini_set('display_errors', 0);
        error_reporting(E_ALL);
        header('Content-Type: application/json; charset=utf-8');

        try {
            $style   = strtolower(trim($_GET['categorie'] ?? 'tous'));
            $lettre  = strtoupper(trim($_GET['lettre']    ?? ''));
            $sort    = trim($_GET['sort']     ?? 'popularite');
            $page    = max(1, (int)($_GET['page']     ?? 1));
            $perPage = min(48, max(1, (int)($_GET['per_page'] ?? 16)));

            $stylesOk = ['tous', 'action', 'drame', 'comédie', 'thriller', 'sci-fi', 'romance', 'horreur', 'anime', 'documentaire', 'crime', 'casual', 'costume', 'vintage'];
            if (!in_array($style, $stylesOk, true)) $style = 'tous';
            if ($lettre !== '' && !preg_match('/^[A-Z]$/', $lettre)) $lettre = '';

            $sortsOk = ['popularite', 'recents', 'alpha-asc', 'alpha-desc', 'looks'];
            if (!in_array($sort, $sortsOk, true)) $sort = 'popularite';

            require_once __DIR__ . '/../models/SerieModel.php';
            $model = new SerieModel();

            $items   = $model->getFiltered($style, $lettre, $sort, $page, $perPage);
            $total   = $model->countFiltered($style, $lettre);
            $hasMore = ($page * $perPage) < $total;

            echo json_encode([
                'items'    => $items,
                'total'    => $total,
                'has_more' => $hasMore,
            ], JSON_UNESCAPED_UNICODE);

        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // ── Page détail d'une série ─────────────────────────────────
    public function show(string $slug): void {
        require_once __DIR__ . '/../models/SerieModel.php';
        $model = new SerieModel();
        $serie = $model->getBySlug($slug);

        if (!$serie) {
            http_response_code(404);
            echo '<h1>404 — Série introuvable</h1>';
            return;
        }

        $title      = $serie['nom'] . ' — Looks & Style · LE DRESSING';
        $personnages = $model->getPersonnages($serie['id']);
        $saisons     = $model->getSaisons($serie['id']);

        require_once __DIR__ . '/../views/series/show.php';
    }

    // ── API looks d'une série ───────────────────────────────────
    public function apiLooks(int $id): void {
        header('Content-Type: application/json; charset=utf-8');

        $personnage = trim($_GET['personnage'] ?? 'tous');
        $saison     = max(0, (int)($_GET['saison'] ?? 0));
        $sort       = trim($_GET['sort']       ?? 'popularite');
        $page       = max(1, (int)($_GET['page']     ?? 1));
        $perPage    = min(48, max(1, (int)($_GET['per_page'] ?? 16)));

        $sortsOk = ['popularite', 'recents', 'alpha-asc'];
        if (!in_array($sort, $sortsOk, true)) $sort = 'popularite';

        require_once __DIR__ . '/../models/SerieModel.php';
        $model = new SerieModel();

        $items   = $model->getLooks($id, $personnage, $saison, $sort, $page, $perPage);
        $total   = $model->countLooks($id, $personnage, $saison);
        $hasMore = ($page * $perPage) < $total;

        echo json_encode([
            'looks'    => $items,
            'total'    => $total,
            'has_more' => $hasMore,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}