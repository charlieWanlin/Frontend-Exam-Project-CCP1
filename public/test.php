<?php
require_once __DIR__ . '/../app/core/Database.php';

$db = getDB();

if ($db) {
    echo "✅ Connexion à la BDD réussie !";
} else {
    echo "❌ Échec de la connexion à la BDD.";
}