<?php
// app/models/CelebrityModel.php

require_once __DIR__ . '/../core/Database.php';

class CelebrityModel {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Home — 8 célébs les plus populaires
    public function getPopulaires(int $limite = 8): array {
        $stmt = $this->db->prepare(
            "SELECT id, nom, slug, photo, nb_looks
             FROM celebrities
             ORDER BY popularite DESC
             LIMIT :limite"
        );
        // LIMIT exige bindValue + PARAM_INT, pas execute([])
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Page catalogue — toutes les célébs
    public function getToutes(): array {
        $stmt = $this->db->prepare(
            "SELECT id, nom, slug, photo, categorie, nb_looks
             FROM celebrities
             ORDER BY popularite DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Page détail — une céléb par son slug
    public function getParSlug(string $slug): array|false {
        $stmt = $this->db->prepare(
            "SELECT * FROM celebrities WHERE slug = :slug"
        );
        // Chaîne → execute([]) direct, plus besoin de bindValue
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }
}