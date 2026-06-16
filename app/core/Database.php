<?php

// : PDO veut dire PHP Data Objects, c'est une interface pour accéder à une base de données depuis PHP
function getDB(): PDO {
    
    //  static: survit entre les appels, connexion créée une seule fois
    static $pdo = null;

    if ($pdo === null) {
        
    // DIR : chemin du fichier courant, ici Database.php

        //  require : chargé ici pour rester dans le/ bon scope
       $config = require __DIR__ . '/../../config/database.php';

        
        //  : chaîne de connexion MySQL dsn = Data Source Name
        $dsn = "mysql:host=" . $config['host'] .
               ";dbname="  . $config['dbname'] .
               ";charset=" . $config['charset'];
        
        $pdo = new PDO(
            $dsn,
            $config['user'],
            $config['password'],
            [
                //  PDO:: ATTR_ERMODE : Lève une exception si erreur SQL — évite les bugs silencieux où le code continue avec des données cassées
    
                PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
                // PDO::ATTR_DEFAULT_FETCH_MODE : retourne des tableaux associatifs
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // PDO::ATTR_EMULATE_PREPARES : vraies requêtes préparées, anti injection SQL
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }
    
    //  return: retourne l'objet PDO actif
    return $pdo;
}