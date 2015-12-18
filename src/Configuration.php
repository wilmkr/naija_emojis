<?php

namespace Wilson\Source;

use Dotenv\Dotenv;

class Configuration
{
    public static function load()
    {
        // $dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
        // $dotenv->load();

        if (! getenv('APP_ENV') || getenv('APP_ENV')=="local") {
            // load config values from .env file if APP_ENV is not found.
            // APP_ENV is set on Heroku server
            $dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
            $dotenv->load();
        }
    }
}