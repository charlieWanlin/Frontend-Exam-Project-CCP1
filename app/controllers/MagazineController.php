<?php

class MagazineController {

    public function index(): void {
        $title = 'Magazine — LE DRESSING';
        require_once __DIR__ . '/../views/magazine/index.php';
    }
}