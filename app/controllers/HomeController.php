<?php
// app/controllers/HomeController.php

class HomeController {

    public function index(): void {
        $title = 'Accueil — LE DRESSING';

        require_once __DIR__ . '/../models/CelebrityModel.php';
        require_once __DIR__ . '/../models/SerieModel.php';

        // getFiltered() trie par popularité DESC par défaut, on limite à 4 résultats
        $celebrites = (new CelebrityModel())->getFiltered(perPage: 4);
        $series     = (new SerieModel())->getFiltered(perPage: 4);

        require_once __DIR__ . '/../views/home/index.php';
    }
}