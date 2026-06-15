<?php

class Panier
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // ── Récupérer tous les items (user ou session) ───────────────────
    public function getItems(int $userId = null, string $sessionId = null): array
    {
        if ($userId) {
            $stmt = $this->db->prepare('SELECT * FROM panier_items WHERE user_id = ? ORDER BY created_at ASC');
            $stmt->execute([$userId]);
        } else {
            $stmt = $this->db->prepare('SELECT * FROM panier_items WHERE session_id = ? ORDER BY created_at ASC');
            $stmt->execute([$sessionId]);
        }

        return $stmt->fetchAll();
    }

    // ── Ajouter un item ──────────────────────────────────────────────
    public function addItem(
        int    $lookId,
        string $nom,
        float  $prix,
        string $image   = '',
        string $taille  = '',
        int    $userId  = null,
        string $sessionId = null
    ): void {
        // Déjà dans le panier ? → incrémenter quantité
        $existing = $this->findItem($lookId, $taille, $userId, $sessionId);

        if ($existing) {
            $stmt = $this->db->prepare(
                'UPDATE panier_items SET quantite = quantite + 1 WHERE id = ?'
            );
            $stmt->execute([$existing['id']]);
            return;
        }

        $stmt = $this->db->prepare(
            'INSERT INTO panier_items (user_id, session_id, look_id, nom, image, prix, taille, quantite)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1)'
        );
        $stmt->execute([$userId, $sessionId, $lookId, $nom, $image, $prix, $taille]);
    }

    // ── Mettre à jour la quantité ────────────────────────────────────
    public function updateQuantite(int $itemId, int $quantite, int $userId = null, string $sessionId = null): void
    {
        if ($quantite <= 0) {
            $this->removeItem($itemId, $userId, $sessionId);
            return;
        }

        if ($userId) {
            $stmt = $this->db->prepare('UPDATE panier_items SET quantite = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([$quantite, $itemId, $userId]);
        } else {
            $stmt = $this->db->prepare('UPDATE panier_items SET quantite = ? WHERE id = ? AND session_id = ?');
            $stmt->execute([$quantite, $itemId, $sessionId]);
        }
    }

    // ── Supprimer un item ────────────────────────────────────────────
    public function removeItem(int $itemId, int $userId = null, string $sessionId = null): void
    {
        if ($userId) {
            $stmt = $this->db->prepare('DELETE FROM panier_items WHERE id = ? AND user_id = ?');
            $stmt->execute([$itemId, $userId]);
        } else {
            $stmt = $this->db->prepare('DELETE FROM panier_items WHERE id = ? AND session_id = ?');
            $stmt->execute([$itemId, $sessionId]);
        }
    }

    // ── Vider le panier ──────────────────────────────────────────────
    public function clear(int $userId = null, string $sessionId = null): void
    {
        if ($userId) {
            $stmt = $this->db->prepare('DELETE FROM panier_items WHERE user_id = ?');
            $stmt->execute([$userId]);
        } else {
            $stmt = $this->db->prepare('DELETE FROM panier_items WHERE session_id = ?');
            $stmt->execute([$sessionId]);
        }
    }

    // ── Merge session → user au login ────────────────────────────────
    public function mergeSession(string $sessionId, int $userId): void
    {
        $sessionItems = $this->getItems(null, $sessionId);

        foreach ($sessionItems as $item) {
            $existing = $this->findItem($item['look_id'], $item['taille'], $userId, null);

            if ($existing) {
                $stmt = $this->db->prepare(
                    'UPDATE panier_items SET quantite = quantite + ? WHERE id = ?'
                );
                $stmt->execute([$item['quantite'], $existing['id']]);
            } else {
                $stmt = $this->db->prepare(
                    'UPDATE panier_items SET user_id = ?, session_id = NULL WHERE id = ?'
                );
                $stmt->execute([$userId, $item['id']]);
            }
        }

        // Supprimer les doublons restants en session
        $stmt = $this->db->prepare('DELETE FROM panier_items WHERE session_id = ?');
        $stmt->execute([$sessionId]);
    }

    // ── Compter les items (pour le badge navbar) ─────────────────────
    public function countItems(int $userId = null, string $sessionId = null): int
    {
        if ($userId) {
            $stmt = $this->db->prepare('SELECT COALESCE(SUM(quantite), 0) FROM panier_items WHERE user_id = ?');
            $stmt->execute([$userId]);
        } else {
            $stmt = $this->db->prepare('SELECT COALESCE(SUM(quantite), 0) FROM panier_items WHERE session_id = ?');
            $stmt->execute([$sessionId]);
        }

        return (int) $stmt->fetchColumn();
    }

    // ── Calculer le sous-total ───────────────────────────────────────
    public function sousTotal(int $userId = null, string $sessionId = null): float
    {
        if ($userId) {
            $stmt = $this->db->prepare('SELECT COALESCE(SUM(prix * quantite), 0) FROM panier_items WHERE user_id = ?');
            $stmt->execute([$userId]);
        } else {
            $stmt = $this->db->prepare('SELECT COALESCE(SUM(prix * quantite), 0) FROM panier_items WHERE session_id = ?');
            $stmt->execute([$sessionId]);
        }

        return (float) $stmt->fetchColumn();
    }

    // ── Trouver un item existant ─────────────────────────────────────
    private function findItem(int $lookId, string $taille, int $userId = null, string $sessionId = null): ?array
    {
        if ($userId) {
            $stmt = $this->db->prepare(
                'SELECT * FROM panier_items WHERE look_id = ? AND taille = ? AND user_id = ? LIMIT 1'
            );
            $stmt->execute([$lookId, $taille, $userId]);
        } else {
            $stmt = $this->db->prepare(
                'SELECT * FROM panier_items WHERE look_id = ? AND taille = ? AND session_id = ? LIMIT 1'
            );
            $stmt->execute([$lookId, $taille, $sessionId]);
        }

        $row = $stmt->fetch();
        return $row ?: null;
    }
}