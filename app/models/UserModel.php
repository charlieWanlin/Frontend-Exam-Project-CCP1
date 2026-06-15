<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // ────────────────────────────────────────────
    // Créer un utilisateur
    // ────────────────────────────────────────────
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (prenom, nom, email, password, verify_token, email_verified)
             VALUES (?, ?, ?, ?, ?, 0)'
        );
        $stmt->execute([
            $data['prenom'],
            $data['nom'],
            $data['email'],
            $data['password'],
            $data['verify_token'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    // ────────────────────────────────────────────
    // Trouver par e-mail (exclut les supprimés)
    // ────────────────────────────────────────────
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE email = ? AND deleted_at IS NULL LIMIT 1'
        );
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // ────────────────────────────────────────────
    // Trouver par id (sans le champ password)
    // ────────────────────────────────────────────
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, prenom, nom, email, role, avatar, bio, email_verified, created_at
             FROM users
             WHERE id = ? AND deleted_at IS NULL
             LIMIT 1'
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // ────────────────────────────────────────────
    // Trouver par id AVEC le champ password
    // Utilisé uniquement pour vérifier le mot de passe
    // avant une action sensible (suppression, changement pwd)
    // ────────────────────────────────────────────
    public function findByIdWithPassword(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, prenom, nom, email, password, role, avatar, bio, email_verified, created_at
             FROM users
             WHERE id = ? AND deleted_at IS NULL
             LIMIT 1'
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // ────────────────────────────────────────────
    // Email déjà utilisé ?
    // ────────────────────────────────────────────
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    // ────────────────────────────────────────────
    // Confirmer l'email via token
    // Retourne l'user complet pour pouvoir ouvrir
    // la session directement après confirmation
    // ────────────────────────────────────────────
    public function verifyEmail(string $token): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, prenom, nom, email, role
             FROM users
             WHERE verify_token = ? AND email_verified = 0
             LIMIT 1'
        );
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return null;

        $stmt = $this->db->prepare(
            'UPDATE users SET email_verified = 1, verify_token = NULL WHERE id = ?'
        );
        $stmt->execute([$user['id']]);

        return $user;
    }

    // ────────────────────────────────────────────
    // Mot de passe oublié — stocker le token
    // ────────────────────────────────────────────
    public function setResetToken(int $id, string $token): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users
             SET reset_token = ?, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR)
             WHERE id = ?'
        );
        $stmt->execute([$token, $id]);
    }

    // ────────────────────────────────────────────
    // Trouver par reset_token (non expiré)
    // ────────────────────────────────────────────
    public function findByResetToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users
             WHERE reset_token = ?
               AND reset_token_expires > NOW()
               AND deleted_at IS NULL
             LIMIT 1'
        );
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // ────────────────────────────────────────────
    // Mettre à jour le mot de passe
    // ────────────────────────────────────────────
    public function updatePassword(int $id, string $newPasswordHash): void
    {
        $stmt = $this->db->prepare(
            'UPDATE users
             SET password = ?, reset_token = NULL, reset_token_expires = NULL
             WHERE id = ?'
        );
        $stmt->execute([$newPasswordHash, $id]);
    }

    // ────────────────────────────────────────────
    // Mettre à jour le profil (prénom, nom, email)
    // ────────────────────────────────────────────
    public function updateProfile(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET prenom = ?, nom = ?, email = ? WHERE id = ?'
        );
        return $stmt->execute([$data['prenom'], $data['nom'], $data['email'], $id]);
    }

    // ────────────────────────────────────────────
    // Supprimer le compte
    // panier_items a ON DELETE CASCADE sur user_id
    // donc supprimé automatiquement avec l'user
    // ────────────────────────────────────────────
    public function delete(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            // Supprime explicitement les items du panier
            // (ON DELETE CASCADE le ferait aussi, mais c'est plus sûr)
            $this->db->prepare(
                'DELETE FROM panier_items WHERE user_id = ?'
            )->execute([$id]);

            // Supprime l'utilisateur
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);

            $this->db->commit();
            return $stmt->rowCount() > 0;

        } catch (\Throwable $e) {
            $this->db->rollBack();
            error_log('[UserModel::delete] ' . $e->getMessage());
            return false;
        }
    }
}