<?php

require 'vendor/autoload.php';

use Slim\Slim;

$app = new Slim();

// Define routes
$app->get('/hello', function () {
    echo "Hello Wil.";
});

// Run the Slim application
$app->run();