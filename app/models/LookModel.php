<?php
// app/models/LookModel.php

require_once __DIR__ . '/../core/Database.php';

class LookModel {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    // Tous les looks d'une céléb
    public function getByCelebrity(int $celebrityId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM looks
             WHERE celebrity_id = :id
             ORDER BY popularite DESC"
        );
        $stmt->execute([':id' => $celebrityId]);
        return $stmt->fetchAll();
    }

    // Tous les looks d'une série
    public function getBySerie(int $serieId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM looks
             WHERE serie_id = :id
             ORDER BY popularite DESC"
        );
        $stmt->execute([':id' => $serieId]);
        return $stmt->fetchAll();
    }

    // Un look + ses vêtements (JOIN)
    public function getAvecVetements(int $lookId): array|false {
        // D'abord le look
        $stmt = $this->db->prepare(
            "SELECT * FROM looks WHERE id = :id"
        );
        $stmt->execute([':id' => $lookId]);
        $look = $stmt->fetch();

        if (!$look) return false;

        // Puis ses vêtements
        $stmt2 = $this->db->prepare(
            "SELECT * FROM vetements
             WHERE look_id = :id
             ORDER BY numero ASC"
        );
        $stmt2->execute([':id' => $lookId]);
        $look['vetements'] = $stmt2->fetchAll();

        return $look;
    }
}