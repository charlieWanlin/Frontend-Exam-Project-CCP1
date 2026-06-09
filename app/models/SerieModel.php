<?php

require_once __DIR__ . '/../core/Database.php';

class SerieModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getDB();
    }

    // ── Héro ───────────────────────────────────────────────────
    public function getForHero(int $limit = 24): array {
        $stmt = $this->pdo->prepare(
            'SELECT nom, photo FROM series
             WHERE photo IS NOT NULL
             ORDER BY popularite DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Catalogue filtré + paginé ───────────────────────────────
    public function getFiltered(
        string $style   = 'tous',
        string $lettre  = '',
        string $sort    = 'popularite',
        int    $page    = 1,
        int    $perPage = 16
    ): array {
        $where  = [];
        $params = [];

        if ($style !== 'tous') {
            $where[]        = 'style = :style';
            $params[':style'] = $style;
        }
        if ($lettre !== '') {
            $where[]          = 'nom LIKE :lettre';
            $params[':lettre'] = $lettre . '%';
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $orderSQL = match ($sort) {
            'recents'    => 'created_at DESC',
            'alpha-asc'  => 'nom ASC',
            'alpha-desc' => 'nom DESC',
            'looks'      => 'nb_looks DESC',
            default      => 'popularite DESC',
        };

        $offset = ($page - 1) * $perPage;

        $sql = "SELECT id, nom, slug, photo, style, description, nb_looks, popularite
                FROM series
                $whereSQL
                ORDER BY $orderSQL
                LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered(string $style = 'tous', string $lettre = ''): int {
        $where  = [];
        $params = [];

        if ($style !== 'tous') {
            $where[]        = 'style = :style';
            $params[':style'] = $style;
        }
        if ($lettre !== '') {
            $where[]          = 'nom LIKE :lettre';
            $params[':lettre'] = $lettre . '%';
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM series $whereSQL");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // ── Page détail (show) ──────────────────────────────────────
    public function getBySlug(string $slug): ?array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM series WHERE slug = :slug LIMIT 1'
        );
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // ── Looks d'une série ───────────────────────────────────────
    public function getLooks(
        int    $serie_id,
        string $personnage = 'tous',
        int    $saison     = 0,
        string $sort       = 'popularite',
        int    $page       = 1,
        int    $perPage    = 16
    ): array {
        $where  = ['serie_id = :sid'];
        $params = [':sid' => $serie_id];

        if ($personnage !== 'tous') {
            $where[]              = 'personnage = :personnage';
            $params[':personnage'] = $personnage;
        }
        if ($saison > 0) {
            $where[]        = 'saison = :saison';
            $params[':saison'] = $saison;
        }

        $whereSQL = 'WHERE ' . implode(' AND ', $where);
        $orderSQL = match ($sort) {
            'recents'    => 'created_at DESC',
            'alpha-asc'  => 'titre ASC',
            default      => 'popularite DESC',
        };

        $offset = ($page - 1) * $perPage;

        $sql = "SELECT id, titre, photo, categorie, personnage, saison, popularite
                FROM looks
                $whereSQL
                ORDER BY $orderSQL
                LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countLooks(int $serie_id, string $personnage = 'tous', int $saison = 0): int {
        $where  = ['serie_id = :sid'];
        $params = [':sid' => $serie_id];

        if ($personnage !== 'tous') {
            $where[]              = 'personnage = :personnage';
            $params[':personnage'] = $personnage;
        }
        if ($saison > 0) {
            $where[]        = 'saison = :saison';
            $params[':saison'] = $saison;
        }

        $whereSQL = 'WHERE ' . implode(' AND ', $where);
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM looks $whereSQL");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // ── Personnages disponibles pour une série ──────────────────
    public function getPersonnages(int $serie_id): array {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT personnage FROM looks
             WHERE serie_id = :sid AND personnage IS NOT NULL
             ORDER BY personnage ASC'
        );
        $stmt->execute([':sid' => $serie_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // ── Saisons disponibles pour une série ─────────────────────
    public function getSaisons(int $serie_id): array {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT saison FROM looks
             WHERE serie_id = :sid AND saison IS NOT NULL
             ORDER BY saison ASC'
        );
        $stmt->execute([':sid' => $serie_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}