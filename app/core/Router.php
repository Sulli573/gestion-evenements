<?php

class Router {
    private static $routes = [];

    /**
     * Ajouter une route GET
     */
    public static function get($uri, $controllerMethod) {
        self::$routes['GET'][$uri] = $controllerMethod;
    }

    /**
     * Ajouter une route POST
     */
    public static function post($uri, $controllerMethod) {
        self::$routes['POST'][$uri] = $controllerMethod;
    }

    /**
     * Démarrer le routeur et exécuter la bonne méthode
     */
    public static function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Vérifier si la route existe
        if (isset(self::$routes[$requestMethod][$requestUri])) {
            $handler = self::$routes[$requestMethod][$requestUri];

            // Récupérer les paramètres GET ou POST
            $params = ($requestMethod === 'GET') ? $_GET : $_POST;

            // Vérification CSRF pour les requêtes POST
            if ($requestMethod === 'POST' && (!isset($_SESSION['csrf_token']) || $_SESSION['csrf_token'] !== ($params['csrf_token'] ?? ''))) {
                echo json_encode(["status" => "error", "message" => "Token CSRF invalide"]);
                exit;
            }

            // Appeler la méthode du contrôleur avec les paramètres
            echo json_encode(call_user_func_array($handler, [$params]));
        } else {
            // Route introuvable
            echo json_encode(["status" => "error", "message" => "Route non trouvée"]);
        }
    }
}