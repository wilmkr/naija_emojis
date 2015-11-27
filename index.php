<?php

require 'vendor/autoload.php';

use Slim\Slim;
use Wilson\Source\Controllers\UserController;
use Wilson\Source\Controllers\AuthController;
use Wilson\Source\Controllers\EmojiController;

$app = new Slim(['debug' => true]);

/**
 *  Create a new user
 */
$app->post('/register', function () use ($app) {
    echo UserController::register($app);
});

// $app->post('/auth/login', function () {
//     echo "login";
// });

// $app->get('/auth/logout', function () {
//     echo "logout";
// });

/**
 *  Create an emoji
 */
$app->post('/emojis', function () use ($app) {
    echo EmojiController::createEmoji($app);
});

/**
 * Show all emojis;
 */
$app->get('/emojis', function () use ($app) {
    echo EmojiController::getAllEmojis($app);
});

/**
 *  Get a single emoji
 */
$app->get('/emojis/:id', function ($id) use ($app) {
    echo EmojiController::findEmoji($app, $id);
});

// $app->put('/emojis/{id}', function () {
//     echo "updates an emoji";
// });

// $app->patch('/emojis/{id}', function () {
//     echo "partialy updates an emoji";
// });

// $app->delete('/emojis/{id}', function () {
//     echo "deletes an emoji";
// });

// Run the Slim application
$app->run();