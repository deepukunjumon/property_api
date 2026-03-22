<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../routes/api.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

route($method, $uri);