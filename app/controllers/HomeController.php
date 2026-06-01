<?php
// app/controllers/HomeController.php

class HomeController {

    public function index(): void {
        $title = 'Accueil — LE DRESSING';

        // __DIR__ = app/controllers/
        // donc ../models/ = app/models/
        require_once __DIR__ . '/../models/CelebrityModel.php';
        require_once __DIR__ . '/../models/SerieModel.php';

        $celebrites = (new CelebrityModel())->getPopulaires(4);
        $series     = (new SerieModel())->getPopulaires(4);

        require_once __DIR__ . '/../views/home/index.php';
    }
}