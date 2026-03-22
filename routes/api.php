<?php

require_once __DIR__ . '/../src/Controllers/PropertyController.php';

function route($method, $uri) {
    $path = parse_url($uri, PHP_URL_PATH);
    $controller = new PropertyController();

    // Route definitions
    $routes = [
        'GET' => [
            '#^/properties$#'             => 'getPropertyList',
            '#^/properties/([a-z0-9]+)$#' => 'getPropertyDetails',
        ],
        'POST' => [
            '#^/properties$#'                     => 'createProperty',
            '#^/properties/([a-z0-9]+)/reviews$#' => 'addReview',
        ],
    ];

    if (isset($routes[$method])) {
        foreach ($routes[$method] as $pattern => $action) {
            if (preg_match($pattern, $path, $matches)) {
                
                if (isset($matches[1])) {
                    Request::setArgs(['id' => $matches[1]]);
                }

                return $controller->$action();
            }
        }
    }

    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Route not found']);
}