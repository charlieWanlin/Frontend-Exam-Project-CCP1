<?php

class Favoris
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // ── Récupérer tous les favoris d'un user ─────────────────────────
    public function getAll(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM favoris WHERE user_id = ? ORDER BY created_at DESC'
        );
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    // ── Ajouter un favori ────────────────────────────────────────────
    public function add(int $userId, int $lookId, string $nom, string $image = '', float $prix = 0, string $slug = ''): void
    {
        // INSERT IGNORE → pas d'erreur si déjà en favori (UNIQUE KEY)
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO favoris (user_id, look_id, nom, image, prix, slug)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $lookId, $nom, $image, $prix, $slug]);
    }

    // ── Retirer un favori ────────────────────────────────────────────
    public function remove(int $userId, int $lookId): void
    {
        $stmt = $this->db->prepare(
            'DELETE FROM favoris WHERE user_id = ? AND look_id = ?'
        );
        $stmt->execute([$userId, $lookId]);
    }

    // ── Toggle (add / remove) ────────────────────────────────────────
    public function toggle(int $userId, int $lookId, string $nom, string $image = '', float $prix = 0, string $slug = ''): bool
    {
        if ($this->isFavori($userId, $lookId)) {
            $this->remove($userId, $lookId);
            return false; // retiré
        }

        $this->add($userId, $lookId, $nom, $image, $prix, $slug);
        return true; // ajouté
    }

    // ── Vérifier si un look est en favori ────────────────────────────
    public function isFavori(int $userId, int $lookId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM favoris WHERE user_id = ? AND look_id = ? LIMIT 1'
        );
        $stmt->execute([$userId, $lookId]);

        return (bool) $stmt->fetch();
    }

    // ── Récupérer tous les look_id en favori (pour marquer les cœurs) ─
    public function getFavoriIds(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT look_id FROM favoris WHERE user_id = ?'
        );
        $stmt->execute([$userId]);

        return array_column($stmt->fetchAll(), 'look_id');
    }

    // ── Compter les favoris ──────────────────────────────────────────
    public function count(int $userId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM favoris WHERE user_id = ?'
        );
        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }
}