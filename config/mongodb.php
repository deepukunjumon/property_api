<?php

require_once __DIR__ . '/../vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");

return $client->property_db->reviews;