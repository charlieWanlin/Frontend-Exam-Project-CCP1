<?php
require_once __DIR__ . '/../models/UserModel.php';

class CompteController
{
    private User $user;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $this->user = new User();
    }

    // GET /mon-compte
    public function index(): void
    {
        $currentUser = $this->user->findById((int) $_SESSION['user_id']);

        if (!$currentUser) {
            session_destroy();
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/account/index.php';
    }

    // GET /mon-compte/modifier
    public function editPage(): void
    {
        $currentUser = $this->user->findById((int) $_SESSION['user_id']);

        if (!$currentUser) {
            session_destroy();
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/account/index.php';
    }

    // POST /api/compte/modifier
    public function update(): void
    {
        header('Content-Type: application/json');

        $prenom = trim($_POST['prenom'] ?? '');
        $nom    = trim($_POST['nom']    ?? '');
        $email  = trim($_POST['email']  ?? '');

        if (!$prenom || !$nom || !$email) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Adresse e-mail invalide.']);
            exit;
        }

        $updated = $this->user->updateProfile((int) $_SESSION['user_id'], [
            'prenom' => $prenom,
            'nom'    => $nom,
            'email'  => $email,
        ]);

        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Profil mis à jour.' : 'Erreur lors de la mise à jour.',
        ]);
        exit;
    }

    // POST /api/compte/mot-de-passe
    public function updatePassword(): void
    {
        header('Content-Type: application/json');

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password']      ?? '';
        $confirm = $_POST['confirm_password']  ?? '';

        if (!$current || !$new || !$confirm) {
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
            exit;
        }

        if ($new !== $confirm) {
            echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
            exit;
        }

        if (strlen($new) < 8) {
            echo json_encode(['success' => false, 'message' => 'Le mot de passe doit faire au moins 8 caractères.']);
            exit;
        }

        $currentUser = $this->user->findByIdWithPassword((int) $_SESSION['user_id']);

        if (!$currentUser) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
            exit;
        }

        if (!password_verify($current, $currentUser['password'])) {
            echo json_encode(['success' => false, 'message' => 'Mot de passe actuel incorrect.']);
            exit;
        }

        $this->user->updatePassword(
            (int) $_SESSION['user_id'],
            password_hash($new, PASSWORD_DEFAULT)
        );

        echo json_encode(['success' => true, 'message' => 'Mot de passe mis à jour.']);
        exit;
    }

    // POST /api/compte/supprimer
    public function delete(): void
    {
        header('Content-Type: application/json');

        $password = $_POST['password'] ?? '';

        if (!$password) {
            echo json_encode(['success' => false, 'message' => 'Mot de passe requis.']);
            exit;
        }

        $currentUser = $this->user->findByIdWithPassword((int) $_SESSION['user_id']);

        if (!$currentUser) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
            exit;
        }

        if (!password_verify($password, $currentUser['password'])) {
            echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect.']);
            exit;
        }

        $deleted = $this->user->delete((int) $_SESSION['user_id']);

        if ($deleted) {
            session_destroy();
            echo json_encode(['success' => true, 'redirect' => '/']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
        }
        exit;
    }
}