<?php

namespace Wilson\Source;

use Slim\Slim;

class OutputFormatter
{
    /**
     * This method formats the output to be in proper JSON format
     * @param  Slim     $app
     * @param  integer  $statusCode
     * @param  string   $message
     */
    public static function formatOutput(Slim $app, $statusCode, $message)
    {
        $responseMessage = [
            'Status' => $statusCode,
            'Message' => $message
        ];

        $app->halt($statusCode, json_encode($responseMessage));
    }
}