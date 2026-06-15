<?php

require_once __DIR__ . '/../core/Database.php';

class SearchModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getDB();
    }

    public function searchCelebrities(string $q, int $limit = 4): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT nom, slug, photo, categorie
               FROM celebrities
              WHERE nom LIKE :q
           ORDER BY popularite DESC
              LIMIT :limit'
        );
        $stmt->bindValue(':q',     '%' . $q . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit,          PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchSeries(string $q, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT nom, slug, photo, style
               FROM series
              WHERE nom LIKE :q
           ORDER BY popularite DESC
              LIMIT :limit'
        );
        $stmt->bindValue(':q',     '%' . $q . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit,          PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchLooks(string $q, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT l.id, l.titre, l.photo, l.categorie,
                    c.slug AS celeb_slug,
                    s.slug AS serie_slug
               FROM looks l
          LEFT JOIN celebrities c ON c.id = l.celebrity_id
          LEFT JOIN series      s ON s.id = l.serie_id
              WHERE l.titre LIKE :q
           ORDER BY l.popularite DESC
              LIMIT :limit'
        );
        $stmt->bindValue(':q',     '%' . $q . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit,          PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}