<?php

class Router {

    // Tableau qui stocke toutes les routes déclarées
    // Structure :
    // [
    //     'GET' => [
    //         '/clients'    => [ClientController::class, 'index'],
    //         '/client/:id' => [ClientController::class, 'show'],
    //         '/dashboard'  => [DashboardController::class, 'index'],
    //     ],
    //     'POST' => [
    //         '/login' => [AuthController::class, 'store'],
    //     ]
    // ]
    private array $routes = [];


    // Enregistre une route GET dans le tableau des routes
    // Exemple d'appel : $router->get('/clients', [ClientController::class, 'index']);
    public function get(string $uri, array $action): void {
        // $uri    = '/clients'                           → l'URL à écouter
        // $action = [ClientController::class, 'index']  → le controller + la méthode à appeler
        // On stocke : "si GET /clients arrive → appelle ClientController::index"
        $this->routes['GET'][$uri] = $action;
    }


    // Enregistre une route POST dans le tableau des routes
    // Exemple d'appel : $router->post('/login', [AuthController::class, 'store']);
    public function post(string $uri, array $action): void {
        // Identique à get() mais pour les requêtes POST (formulaires)
        $this->routes['POST'][$uri] = $action;
    }


    // Découpe une URI en morceaux propres, séparés par '/'
    // Exemple : '/client/42' → ['client', '42']
    private function splitUri(string $uri): array {
        return array_values(array_filter(explode('/', $uri)));
        // Étape 1 — explode('/', '/client/42')  → ['', 'client', '42']
        //            coupe la string à chaque '/'
        //            le '' vient du '/' au début de l'URL
        //
        // Étape 2 — array_filter(...)           → ['client', '42']
        //            supprime les valeurs vides (le '' du début)
        //
        // Étape 3 — array_values(...)           → ['client', '42']
        //            remet les index à 0, 1, 2...
        //            (array_filter conserve les anciens index, ex: [1 => 'client', 2 => '42'])
        //            array_values repart de 0
    }


    // Cherche une route qui correspond à l'URI reçue
    // Retourne un tableau ['action' => [...], 'params' => [...]] si trouvée
    // Retourne null si aucune route ne correspond → 404
    private function match(string $httpMethod, string $uri): ?array {
        // ?array = retourne soit un array, soit null (le ? signifie "nullable")

        // Découpe l'URI reçue en morceaux
        // ex: '/client/42' → ['client', '42']
        $uriParts = $this->splitUri($uri);

        // Parcourt toutes les routes déclarées pour cette méthode HTTP
        // ex: toutes les routes GET
        foreach ($this->routes[$httpMethod] as $routeUri => $action) {
            // $routeUri = '/client/:id'                      → la route déclarée
            // $action   = [ClientController::class, 'show'] → ce qu'on appellera si ça matche

            // Découpe la route déclarée en morceaux
            // ex: '/client/:id' → ['client', ':id']
            $routeParts = $this->splitUri($routeUri);

            // Si le nombre de morceaux est différent → c'est pas la bonne route
            // ex: ['client', '42'] a 2 morceaux
            //     ['dashboard']    a 1 morceau → pas la même route, on passe
            if (count($routeParts) !== count($uriParts)) {
                continue; // passe directement à la route suivante dans le foreach
            }

            // Tableau qui va accumuler les paramètres dynamiques trouvés
            // ex: ['id' => '42']
            $params = [];

            // On suppose que la route correspond, jusqu'à preuve du contraire
            // Si on trouve un morceau qui ne correspond pas, on passe à false
            $matched = true;

            // Compare les morceaux un par un avec une boucle for
            // $i commence à 0 et avance jusqu'au dernier morceau
            for ($i = 0; $i < count($routeParts); $i++) {

                // Est-ce que ce morceau de la route commence par ':' ?
                // ex: ':id', ':slug', ':clientId' → oui
                // ex: 'client', 'dashboard'        → non
                if (str_starts_with($routeParts[$i], ':')) {

                    // C'est un paramètre dynamique → matche n'importe quelle valeur
                    // On récupère le nom du paramètre en retirant le ':'
                    // ex: ':id' → 'id'
                    $paramName = ltrim($routeParts[$i], ':');
                    // ltrim($string, ':') → supprime le ':' au début de la string
                    // 'l' dans ltrim = left (gauche) → supprime uniquement à gauche

                    // On retient la valeur correspondante dans l'URI reçue
                    // ex: $uriParts[1] = '42'  →  $params['id'] = '42'
                    $params[$paramName] = $uriParts[$i];

                } else {

                    // C'est un segment fixe → les deux morceaux doivent être identiques
                    // ex: routeParts[0] = 'client' doit être égal à uriParts[0] = 'client'
                    if ($routeParts[$i] !== $uriParts[$i]) {
                        // Les morceaux sont différents → c'est pas la bonne route
                        $matched = false;
                        break; // inutile de continuer la comparaison, on sort du for
                    }
                }
            }

            // Si tous les morceaux ont matché → on a trouvé la bonne route
            if ($matched) {
                return [
                    'action' => $action,  // ex: [ClientController::class, 'show']
                    'params' => $params,  // ex: ['id' => '42']
                ];
            }
            // Sinon on continue le foreach vers la route suivante
        }

        // On a parcouru toutes les routes sans trouver → null
        return null;
    }


    // Point d'entrée principal : reçoit la requête HTTP et appelle le bon controller
    public function dispatch(): void {

        // 1. Récupère la méthode HTTP et l'URI depuis la requête
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        // $_SERVER['REQUEST_METHOD'] = 'GET' ou 'POST' selon la requête

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        // $_SERVER['REQUEST_URI'] peut contenir '/client/42?foo=bar'
        // parse_url(..., PHP_URL_PATH) extrait uniquement le chemin → '/client/42'
        // sans les paramètres GET (?foo=bar)

        // 2. Cherche une route qui correspond à cette méthode + cette URI
        $match = $this->match($httpMethod, $uri);
        // $match = ['action' => [...], 'params' => ['id' => '42']]
        // ou null si aucune route trouvée

        // 3. Si pas trouvé → réponse 404
        if ($match === null) {
            http_response_code(404); // envoie le code HTTP 404 au navigateur
            echo "Page not found";
            return; // arrête dispatch() ici
        }

        // 4. Extrait l'action et les paramètres du résultat
        $action = $match['action']; // ex: [ClientController::class, 'show']
        $params = $match['params']; // ex: ['id' => '42']

        // 5. Appelle le bon controller avec les paramètres
        // call_user_func($action, $params); // Alternative — fait la même chose en une ligne

        [$controllerClass, $controllerMethod] = $action;
        // Déstructure le tableau : $controllerClass = 'ClientController'
        //                          $controllerMethod = 'show'

        $controller = new $controllerClass();
        // Instancie le controller dynamiquement
        // ex: new ClientController()

        $controller->$controllerMethod(...array_values($params));
        // Appelle la méthode avec les paramètres dynamiques
        // ex: $controller->show(42)
        // array_values($params) → prend les valeurs du tableau $params et les met dans le bon ordre
        // ex: ['id' => '42'] → ['42'] → 42 (grâce au ... qui transforme le tableau en arguments séparés)

    }
}