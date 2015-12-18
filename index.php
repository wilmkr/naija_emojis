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
$app->post('/emojis', function () use ($app) {
    echo EmojiController::createEmoji($app);
});

// Show all emojis;
$app->get('/emojis', function () use ($app) {
    echo EmojiController::getAllEmojis($app);
});

// Get a single emoji
$app->get('/emojis/:pos', function ($position) use ($app) {
    echo EmojiController::findEmoji($app, $position);
});

// Partially update an emoji
$app->patch('/emojis/:pos', function ($position) use ($app) {
   echo EmojiController::patchEmoji($app, $position);
});

// Fully update an emoji
$app->put('/emojis/:pos', function ($position) use ($app) {
    echo EmojiController::updateEmoji($app, $position);
});

// Delete an emoji
$app->delete('/emojis/:pos', function ($position) use ($app) {
    echo EmojiController::deleteEmoji($app, $position);
});

$app->get('/test', function () {
    echo "Test output";
});

// Run the Slim application
$app->run();