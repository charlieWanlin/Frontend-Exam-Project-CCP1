<?php
// app/controllers/HomeController.php

require_once __DIR__ . '/../models/CelebrityModel.php';
require_once __DIR__ . '/../models/SerieModel.php';

class HomeController {

    public function index(): void {
        $title = 'Accueil — LE DRESSING';

        $celebrites = (new CelebrityModel())->getPopulaires(8);
        $series     = (new SerieModel())->getPopulaires(8);

        require_once __DIR__ . '/../views/home/index.php';
    }
}