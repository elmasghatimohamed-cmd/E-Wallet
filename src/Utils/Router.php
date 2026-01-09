<?php

namespace Src\Utils;

use Src\Repositories\UserRepository;
use Src\Services\UserService;
use Src\Controllers\AuthController;

class Router
{
    private array $routes = [];

    /*
     * Ajoute une route GET
     */
    public function get(string $path, $callback): void
    {
        $this->addRoute('GET', $path, $callback);
    }

    /**
     * Ajoute une route POST
     */
    public function post(string $path, $callback): void
    {
        $this->addRoute('POST', $path, $callback);
    }

    /**
     * Ajoute une route
     */
    private function addRoute(string $method, string $path, $callback): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    /**
     * Résout la route actuelle
     */
    public function resolve(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                $this->executeCallback($route['callback']);
                return;
            }
        }
    }

    /**
     * Exécute le callback de la route
     */
    private function executeCallback($callback): void
    {
        if (is_array($callback)) {
            // Format [ClassName::class, 'methodName']
            [$class, $method] = $callback;
            $controller = $this->resolveController($class);
            $controller->$method();
        } elseif (is_callable($callback)) {
            // Format function() { ... }
            call_user_func($callback);
        }
    }

    /**
     * Résout le contrôleur avec ses dépendances
     */
    private function resolveController(string $class): object
    {
        // Récupération de la base de données
        $db = require __DIR__ . '/../Config/database.php';

        // Instanciation selon le contrôleur
        switch ($class) {
            case 'Src\Controllers\AuthController':
                $userRepository = new UserRepository($db);
                $userService = new UserService($userRepository);
                return new AuthController($userService);

            // Ajoutez ici vos autres contrôleurs avec leurs dépendances
            // case 'Src\Controllers\ExpenseController':
            //     $expenseRepository = new ExpenseRepository($db);
            //     return new ExpenseController($expenseRepository);

            default:
                // Par défaut, essayer d'instancier sans paramètre
                return new $class();
        }
    }

}