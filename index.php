<?php

require 'vendor/autoload.php';

use Slim\Slim;
use Wilson\Source\Controllers\UserController;
use Wilson\Source\Controllers\EmojiController;

$app = new Slim(['debug' => true]);

// Create a new user
$app->post('/register', function () use ($app) {
    echo UserController::register($app);
});

// Login a user
$app->post('/auth/login', function () use ($app) {
    echo UserController::login($app);
});

// Logout a user
$app->get('/auth/logout', function () use ($app) {
   echo UserController::logout($app);
});

// Create an emoji
$app->post('/emoji', function () use ($app) {
    echo EmojiController::createEmoji($app);
});

// Show all emojis;
$app->get('/emojis', function () use ($app) {
    echo EmojiController::getAllEmojis($app);
});

// Get a single emoji
$app->get('/emoji/:id', function ($id) use ($app) {
    echo EmojiController::findEmoji($app, $id);
});

// Partially update an emoji
$app->patch('/emoji/:id', function ($id) use ($app) {
   echo EmojiController::patchEmoji($app, $id);
});

// Fully update an emoji
$app->put('/emoji/:id', function ($id) use ($app) {
    echo EmojiController::updateEmoji($app, $id);
});

// Delete an emoji
$app->delete('/emoji/:id', function ($id) use ($app) {
    echo EmojiController::deleteEmoji($app, $id);
});

$app->get('/test', function () {
    echo "Test output";
});

// Run the Slim application
$app->run();