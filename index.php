<?php

require 'vendor/autoload.php';

use Slim\Slim;
use Wilson\Source\Controllers\UserController;
use Wilson\Source\Controllers\EmojiController;

$app = new Slim(['debug' => true]);

/**
 *  Create a new user
 */
$app->post('/register', function () use ($app) {
    echo UserController::register($app);
});

/**
 *  Login a user
 */
$app->post('/auth/login', function () use ($app) {
    echo UserController::login($app);
});

/**
 *  Logout a user
 */
$app->get('/auth/logout', function () use ($app) {
   echo UserController::logout($app);
});

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
$app->get('/emojis/:pos', function ($position) use ($app) {
    echo EmojiController::findEmoji($app, $position);
});

// $app->put('/emojis/{id}', function () {
//     echo "updates an emoji";
// });

// $app->patch('/emojis/{id}', function () {
//     echo "partialy updates an emoji";
// });

/**
 *  Delete an emoji
 */
$app->delete('/emojis/:pos', function ($position) use ($app) {
    echo EmojiController::deleteEmoji($app, $position);
});

// Run the Slim application
$app->run();