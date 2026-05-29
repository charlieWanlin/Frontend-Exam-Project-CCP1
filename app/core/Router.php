<?php

class Router {

    // Tableau qui stocke toutes les routes déclarées
    // Structure :
    // [
    //     'GET' => [
    //         '/celebrities'       => ['CelebritiesController', 'index'],
    //         '/celebrities/:slug' => ['CelebritiesController', 'show'],
    //     ],
    //     'POST' => [
    //         '/login' => ['AuthController', 'login'],
    //     ]
    // ]
    private array $routes = [];


    // Enregistre une route GET
    // ex: $router->get('/celebrities', 'CelebritiesController', 'index')
    public function get(string $path, string $controller, string $action): void {
        $this->routes['GET'][$path] = [$controller, $action];
    }


    // Enregistre une route POST
    // ex: $router->post('/login', 'AuthController', 'login')
    public function post(string $path, string $controller, string $action): void {
        $this->routes['POST'][$path] = [$controller, $action];
    }


    // Découpe une URI en morceaux propres, séparés par '/'
    // ex: '/celebrities/johndoe' → ['celebrities', 'johndoe']
    private function splitUri(string $uri): array {
        return array_values(array_filter(explode('/', $uri)));
    }


    // Cherche une route qui correspond à l'URI reçue
    // Retourne ['action' => [...], 'params' => [...]] si trouvée, null sinon
    private function match(string $httpMethod, string $uri): ?array {

        // Si aucune route n'est déclarée pour cette méthode HTTP → null
        if (!isset($this->routes[$httpMethod])) {
            return null;
        }

        // Découpe l'URI reçue en morceaux
        // ex: '/celebrities/johndoe' → ['celebrities', 'johndoe']
        $uriParts = $this->splitUri($uri);

        // Parcourt toutes les routes déclarées pour cette méthode HTTP
        foreach ($this->routes[$httpMethod] as $routePath => $action) {

            // Découpe la route déclarée en morceaux
            // ex: '/celebrities/:slug' → ['celebrities', ':slug']
            $routeParts = $this->splitUri($routePath);

            // Nombre de morceaux différent → pas la bonne route
            if (count($routeParts) !== count($uriParts)) {
                continue;
            }

            // Tableau qui va stocker les paramètres dynamiques trouvés
            // ex: ['slug' => 'johndoe']
            $params  = [];
            $matched = true;

            // Compare les morceaux un par un
            for ($i = 0; $i < count($routeParts); $i++) {

                if (str_starts_with($routeParts[$i], ':')) {
                    // Morceau dynamique → matche n'importe quelle valeur
                    // ex: ':slug' → on retient 'johndoe' dans $params['slug']
                    $paramName          = ltrim($routeParts[$i], ':');
                    $params[$paramName] = $uriParts[$i];

                } else {
                    // Morceau fixe → doit être identique
                    // ex: 'celebrities' doit égaler 'celebrities'
                    if ($routeParts[$i] !== $uriParts[$i]) {
                        $matched = false;
                        break;
                    }
                }
            }

            // Tous les morceaux ont matché → bonne route trouvée
            if ($matched) {
                return [
                    'action' => $action,  // ex: ['CelebritiesController', 'show']
                    'params' => $params,  // ex: ['slug' => 'johndoe']
                ];
            }
        }

        // Aucune route trouvée
        return null;
    }


    // Point d'entrée : reçoit la requête et appelle le bon controller
    public function dispatch(string $url): void {

        $httpMethod = $_SERVER['REQUEST_METHOD'];

        // Nettoie l'URL : supprime les slashes en début/fin
        // ex: '/celebrities/' → '/celebrities'
        $uri = '/' . trim($url, '/');

        // Cherche une route qui correspond
        $match = $this->match($httpMethod, $uri);

        // Aucune route trouvée → 404
        if ($match === null) {
            $this->error404("Aucune route pour : $uri");
            return;
        }

        [$controllerName, $action] = $match['action'];
        $params                    = $match['params'];

        // Charge le fichier du controller
        $controllerFile = '../app/controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            $this->error404("Fichier introuvable : $controllerName");
            return;
        }

        require_once $controllerFile;

        // Vérifie que la classe existe
        if (!class_exists($controllerName)) {
            $this->error404("Classe introuvable : $controllerName");
            return;
        }

        // Vérifie que la méthode existe
        if (!method_exists($controllerName, $action)) {
            $this->error404("Méthode introuvable : $controllerName::$action");
            return;
        }

        // Instancie le controller et appelle la méthode avec les paramètres
        // ex: $controller->show('johndoe')
        $controller = new $controllerName();
        $controller->$action(...array_values($params));
    }


    // Affiche une page 404 propre
    private function error404(string $message = ''): void {
        http_response_code(404);
        echo "<h1>404 — Page introuvable</h1>";
        if ($message) {
            echo "<p><code>$message</code></p>";
        }
    }
}