<?php
// app/models/SerieModel.php

require_once __DIR__ . '/../core/Database.php';

class SerieModel {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getPopulaires(int $limite = 8): array {
        $stmt = $this->db->prepare(
            "SELECT id, nom, slug, photo, style, description, nb_looks
             FROM series
             ORDER BY popularite DESC
             LIMIT :limite"
        );
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getToutes(): array {
        $stmt = $this->db->prepare(
            "SELECT id, nom, slug, photo, style, description, nb_looks
             FROM series
             ORDER BY popularite DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getParSlug(string $slug): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM series WHERE slug = :slug"
        );
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }
}