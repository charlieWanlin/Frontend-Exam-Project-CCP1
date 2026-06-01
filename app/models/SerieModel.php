<?php
// app/models/SerieModel.php

require_once __DIR__ . '/../core/Database.php';

class SerieModel {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Home — 8 séries les plus populaires
    public function getPopulaires(int $limite = 8): array {
        $stmt = $this->db->prepare(
            "SELECT id, nom, slug, photo, nb_looks
             FROM series
             ORDER BY popularite DESC
             LIMIT :limite"
        );
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Page catalogue — toutes les séries
    public function getToutes(): array {
        $stmt = $this->db->prepare(
            "SELECT id, nom, slug, photo, style, nb_looks
             FROM series
             ORDER BY popularite DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Page détail — une série par son slug
    public function getParSlug(string $slug): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM series WHERE slug = :slug"
        );
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }
}