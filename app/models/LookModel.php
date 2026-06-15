<?php
// app/models/LookModel.php

require_once __DIR__ . '/../core/Database.php';

class LookModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // ── Un look par ID ────────────────────────────────────────────
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT l.*,
                    c.nom  AS celebrity_nom,
                    c.slug AS celebrity_slug,
                    s.nom  AS serie_titre,
                    s.slug AS serie_slug
             FROM looks l
             LEFT JOIN celebrities c ON c.id = l.celebrity_id
             LEFT JOIN series      s ON s.id = l.serie_id
             WHERE l.id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ── Vêtements associés à un look ─────────────────────────────
    public function getVetements(int $lookId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM vetements
             WHERE look_id = :id
             ORDER BY numero ASC"
        );
        $stmt->execute([':id' => $lookId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Looks filtrés + paginés (API grille) ─────────────────────
    public function getFiltered(
        string $categorie,
        string $sort,
        int    $page,
        int    $perPage
    ): array {
        [$where, $params] = $this->buildWhere($categorie);

        $orderBy = match ($sort) {
            'popularite' => 'l.popularite DESC',
            'alpha-asc'  => 'l.titre ASC',
            default      => 'l.created_at DESC',
        };

        $offset = ($page - 1) * $perPage;

        $sql = "SELECT l.id, l.titre, l.photo, l.categorie,
                       l.popularite, l.created_at,
                       c.nom  AS celebrity_nom,
                       c.slug AS celebrity_slug
                FROM looks l
                LEFT JOIN celebrities c ON c.id = l.celebrity_id
                $where
                ORDER BY $orderBy
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Compte total pour la pagination ──────────────────────────
    public function countFiltered(string $categorie): int
    {
        [$where, $params] = $this->buildWhere($categorie);

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM looks l $where"
        );
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    // ── Looks liés (même catégorie, hors look courant) ───────────
    public function getRelated(int $lookId, ?string $categorie, int $limit = 4): array
    {
        $stmt = $this->db->prepare(
            "SELECT l.id, l.titre, l.photo, l.categorie,
                    c.nom AS celebrity_nom, c.slug AS celebrity_slug
             FROM looks l
             LEFT JOIN celebrities c ON c.id = l.celebrity_id
             WHERE l.id != :id
               AND l.categorie = :cat
             ORDER BY l.popularite DESC
             LIMIT :lim"
        );
        $stmt->bindValue(':id',  $lookId,    PDO::PARAM_INT);
        $stmt->bindValue(':cat', $categorie);
        $stmt->bindValue(':lim', $limit,     PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Tous les looks d'une célébrité ───────────────────────────
    public function getByCelebrity(int $celebrityId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM looks
             WHERE celebrity_id = :id
             ORDER BY popularite DESC"
        );
        $stmt->execute([':id' => $celebrityId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Tous les looks d'une série ───────────────────────────────
    public function getBySerie(int $serieId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM looks
             WHERE serie_id = :id
             ORDER BY popularite DESC"
        );
        $stmt->execute([':id' => $serieId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Liste des catégories distinctes ──────────────────────────
    public function getCategories(): array
    {
        $stmt = $this->db->query(
            "SELECT DISTINCT categorie FROM looks
             WHERE categorie IS NOT NULL
             ORDER BY categorie ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // ── Liste des séries qui ont des looks ───────────────────────
    public function getSeries(): array
    {
        $stmt = $this->db->query(
            "SELECT DISTINCT s.id, s.nom AS titre, s.slug
             FROM series s
             INNER JOIN looks l ON l.serie_id = s.id
             ORDER BY s.nom ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Helper : clause WHERE + params ───────────────────────────
    private function buildWhere(string $categorie): array
    {
        if ($categorie === 'tous' || $categorie === '') {
            return ['', []];
        }
        return [
            'WHERE l.categorie = :categorie',
            [':categorie' => $categorie],
        ];
    }
}