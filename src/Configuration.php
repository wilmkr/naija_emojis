<?php

namespace Wilson\Source;

use Dotenv\Dotenv;

class Configuration
{
    public static function load()
    {
        $dotenv = new Dotenv($_SERVER['DOCUMENT_ROOT']);
        $dotenv->load();
    }
}