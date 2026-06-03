<?php
// app/models/CelebrityModel.php

require_once __DIR__ . '/../core/Database.php';

class CelebrityModel {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getPopulaires(int $limite = 8): array {
        $stmt = $this->db->prepare(
            "SELECT id, nom, slug, photo, nb_looks
             FROM celebrities
             ORDER BY popularite DESC
             LIMIT :limite"
        );
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(): array {
        $stmt = $this->db->prepare(
            "SELECT id, nom, slug, photo, categorie, nb_looks
             FROM celebrities
             ORDER BY popularite DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getForHero(int $limite = 24): array {
        $stmt = $this->db->prepare(
            "SELECT photo, nom
             FROM celebrities
             ORDER BY RAND()
             LIMIT :limite"
        );
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function buildConditions(string $categorie, string $lettre): array {
        $conditions = [];
        $params     = [];

        if ($categorie !== 'tous' && $categorie !== '') {
            $conditions[] = 'categorie = :categorie';
            $params[':categorie'] = $categorie;
        }

        if ($lettre !== '') {
            $conditions[] = 'nom LIKE :lettre';
            $params[':lettre'] = $lettre . '%';
        }

        return [$conditions, $params];
    }

    public function getFiltered(
        string $categorie = 'tous',
        string $lettre    = '',
        string $sort      = 'popularite',
        int    $page      = 1,
        int    $perPage   = 16
    ): array {
        [$conditions, $params] = $this->buildConditions($categorie, $lettre);

        $where = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $orderBy = match ($sort) {
            'recents'    => 'created_at DESC',
            'alpha-asc'  => 'nom ASC',
            'alpha-desc' => 'nom DESC',
            'looks'      => 'nb_looks DESC',
            default      => 'popularite DESC',
        };

        $offset = ($page - 1) * $perPage;

        $stmt = $this->db->prepare(
            "SELECT id, nom, slug, photo, categorie, nb_looks
             FROM celebrities
             {$where}
             ORDER BY {$orderBy}
             LIMIT :limit OFFSET :offset"
        );

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered(string $categorie = 'tous', string $lettre = ''): int {
        [$conditions, $params] = $this->buildConditions($categorie, $lettre);

        $where = count($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM celebrities {$where}"
        );
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function getParSlug(string $slug): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM celebrities WHERE slug = :slug"
        );
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}