<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/SearchModel.php';

class SearchController
{
    public function index(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');

        // Sécurité : XHR uniquement
        if (
            empty($_SERVER['HTTP_X_REQUESTED_WITH']) ||
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
        ) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        $q = trim($_GET['q'] ?? '');

        if (mb_strlen($q) < 2) {
            echo json_encode(['results' => []], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $model = new SearchModel();

        $results = [];

        // ── Célébrités ──────────────────────────────────────────
        foreach ($model->searchCelebrities($q) as $row) {
            $results[] = [
                'nom'   => $row['nom'],
                'url'   => '/celebrities/' . $row['slug'],
                'photo' => $row['photo'],
                'type'  => 'Célébrité' . ($row['categorie'] ? ' · ' . $row['categorie'] : ''),
            ];
        }

        // ── Séries ──────────────────────────────────────────────
        foreach ($model->searchSeries($q) as $row) {
            $results[] = [
                'nom'   => $row['nom'],
                'url'   => '/series/' . $row['slug'],
                'photo' => $row['photo'],
                'type'  => 'Série' . ($row['style'] ? ' · ' . $row['style'] : ''),
            ];
        }

        // ── Looks ────────────────────────────────────────────────
        foreach ($model->searchLooks($q) as $row) {
            if ($row['celeb_slug']) {
                $url = '/celebrities/' . $row['celeb_slug'] . '/looks/' . $row['id'];
            } elseif ($row['serie_slug']) {
                $url = '/series/' . $row['serie_slug'] . '/looks/' . $row['id'];
            } else {
                $url = '/looks/' . $row['id'];
            }

            $results[] = [
                'nom'   => $row['titre'],
                'url'   => $url,
                'photo' => $row['photo'],
                'type'  => 'Look' . ($row['categorie'] ? ' · ' . $row['categorie'] : ''),
            ];
        }

        echo json_encode(['results' => $results], JSON_UNESCAPED_UNICODE);
        exit;
    }
}