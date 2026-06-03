<?php
class CelebritiesController {

    public function index(): void {
        $title = 'Célébrités — LE DRESSING';

        require_once __DIR__ . '/../models/CelebrityModel.php';
        $heroImages = (new CelebrityModel())->getForHero(24);

        $col1 = array_slice($heroImages, 0,  8);
        $col2 = array_slice($heroImages, 8,  8);
        $col3 = array_slice($heroImages, 16, 8);

        require_once __DIR__ . '/../views/celebrities/index.php';
    }

    public function api(): void {
        header('Content-Type: application/json; charset=utf-8');

        $categorie = strtolower(trim($_GET['categorie'] ?? 'tous'));
        $lettre    = strtoupper(trim($_GET['lettre']    ?? ''));
        $sort      = trim($_GET['sort']      ?? 'popularite');
        $page      = max(1, (int)($_GET['page']     ?? 1));
        $perPage   = min(48, max(1, (int)($_GET['per_page'] ?? 16)));

        $catsOk = ['tous', 'cinéma', 'chant', 'mode', 'sport', 'mannequinat', 'youtube', 'influenceur']; 
        if (!in_array($categorie, $catsOk, true)) {
            $categorie = 'tous';
        }

        if ($lettre !== '' && !preg_match('/^[A-Z]$/', $lettre)) {
            $lettre = '';
        }

        $sortsOk = ['popularite', 'recents', 'alpha-asc', 'alpha-desc', 'looks'];
        if (!in_array($sort, $sortsOk, true)) {
            $sort = 'popularite';
        }

        require_once __DIR__ . '/../models/CelebrityModel.php';
        $model = new CelebrityModel();

        $items   = $model->getFiltered($categorie, $lettre, $sort, $page, $perPage);
        $total   = $model->countFiltered($categorie, $lettre);
        $hasMore = ($page * $perPage) < $total;

        echo json_encode([
            'items'    => $items,
            'total'    => $total,
            'has_more' => $hasMore,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}