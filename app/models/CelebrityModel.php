<?php

require_once __DIR__ . '/../core/Database.php';

class CelebrityModel {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getDB();
    }

    // ── Héro (colonnes défilantes) ──────────────────────────────
    public function getForHero(int $limit = 24): array {
        $stmt = $this->pdo->prepare(
            'SELECT nom, photo FROM celebrities
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
        string $categorie = 'tous',
        string $lettre    = '',
        string $sort      = 'popularite',
        int    $page      = 1,
        int    $perPage   = 16
    ): array {
        $where  = [];
        $params = [];

        if ($categorie !== 'tous') {
            $where[]             = 'categorie = :categorie';
            $params[':categorie'] = $categorie;
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

        $sql = "SELECT id, nom, slug, photo, categorie, nb_looks, popularite
                FROM celebrities
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

    public function countFiltered(string $categorie = 'tous', string $lettre = ''): int {
        $where  = [];
        $params = [];

        if ($categorie !== 'tous') {
            $where[]             = 'categorie = :categorie';
            $params[':categorie'] = $categorie;
        }
        if ($lettre !== '') {
            $where[]          = 'nom LIKE :lettre';
            $params[':lettre'] = $lettre . '%';
        }

        $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM celebrities $whereSQL");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // ── Page détail (show) ──────────────────────────────────────
    public function getBySlug(string $slug): ?array {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM celebrities WHERE slug = :slug LIMIT 1'
        );
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    // ── Looks d'une célébrité ───────────────────────────────────
    public function getLooks(
        int    $celebrity_id,
        string $categorie = 'tous',
        string $sort      = 'popularite',
        int    $page      = 1,
        int    $perPage   = 16
    ): array {
        $where  = ['celebrity_id = :cid'];
        $params = [':cid' => $celebrity_id];

        if ($categorie !== 'tous') {
            $where[]             = 'categorie = :categorie';
            $params[':categorie'] = $categorie;
        }

        $whereSQL = 'WHERE ' . implode(' AND ', $where);
        $orderSQL = match ($sort) {
            'recents'    => 'created_at DESC',
            'alpha-asc'  => 'titre ASC',
            'populaires' => 'popularite DESC',
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

    public function countLooks(int $celebrity_id, string $categorie = 'tous'): int {
        $where  = ['celebrity_id = :cid'];
        $params = [':cid' => $celebrity_id];

        if ($categorie !== 'tous') {
            $where[]             = 'categorie = :categorie';
            $params[':categorie'] = $categorie;
        }

        $whereSQL = 'WHERE ' . implode(' AND ', $where);
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM looks $whereSQL");
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // ── Catégories disponibles pour une célébrité ──────────────
    public function getLookCategories(int $celebrity_id): array {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT categorie FROM looks
             WHERE celebrity_id = :cid AND categorie IS NOT NULL
             ORDER BY categorie ASC'
        );
        $stmt->execute([':cid' => $celebrity_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}