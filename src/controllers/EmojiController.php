<?php

namespace Wilson\Source\Controllers;

use PDO;
use Exception;
use Slim\Slim;
use Wilson\Source\Models\Emoji;
use Wilson\Source\Authenticator;
use Wilson\Source\OutputFormatter;

class EmojiController
{
     /**
     * This method creates an instance of an emoji and stores it in the database.
     * @param  Slim   $app      [Slim instance]
     */
    public static function createEmoji(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        Authenticator::authenticate($app);

        $emoji = new Emoji();

        $emoji->name = Authenticator::checkParamValue($app, "name", $app->request->params('name'));
        $emoji->emoji_char = Authenticator::checkParamValue($app, "emoji_char", $app->request->params('emoji_char'));
        $emoji->category = Authenticator::checkParamValue($app, "category", $app->request->params('category'));
        $emoji->keywords = Authenticator::checkParamValue($app, "keywords", $app->request->params('keywords'));
        $emoji->created_by = Authenticator::checkParamValue($app, "created_by", $app->request->params('created_by'));

        $conn = Emoji::getConnection();
        $stmt = $conn->query("SELECT * FROM emojis WHERE emoji_char='$emoji->emoji_char'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            OutputFormatter::formatOutput($app, 200, "The emoji $emoji->emoji_char already exists.");
        }

        $rows = $emoji->save();

        if($rows > 0) {
            OutputFormatter::formatOutput($app, 201, 'Emoji creation successful.');
        }
        else {
            OutputFormatter::formatOutput($app, 400, 'Emoji creation failed!');
        }
    }

    /**
     * This method fetches all the emojis in the database
     * @param  Slim   $app      [Slim instance]
     * @return json encoded string
     */
    public static function getAllEmojis(Slim $app)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $returnedValue = Emoji::getAll();

        if(is_array($returnedValue)) {

            for($i = 0; $i < count($returnedValue); $i++) {
                $returnedValue[$i]['keywords'] = [$returnedValue[$i]['keywords']];
            }

            return json_encode($returnedValue);
        }

        if(is_string($returnedValue)) {
            OutputFormatter::formatOutput($app, 204, $returnedValue);
        }
    }

    /**
     * This method is used to search for a particular emoji
     * @param  Slim   $app      [Slim instance]
     * @param  $id              [the id of the emoji in the emojis table]
     * @return json encoded string
     */
    public static function findEmoji(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        $returnedValue = Emoji::findById($id);

        if(is_object($returnedValue)) {
            $obj = json_decode($returnedValue->result);
            $obj->keywords = [$obj->keywords];

            return json_encode($obj);
        }

        if(is_string($returnedValue)) {
            OutputFormatter::formatOutput($app, 404, $returnedValue);
        }
    }

    /**
     * This function fully updates an emoji in the database i.e. updates all fields of an emoji record
     * @param  Slim   $app      [Slim instance]
     * @param  $id              [the id of the emoji in the emojis table]
     */
    public static function updateEmoji(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        Authenticator::authenticate($app);

        $emoji = Emoji::findById($id);

        if(is_string($emoji)) {
            OutputFormatter::formatOutput($app, 404, $emoji);
        }

        $emoji->name = Authenticator::checkParamValue($app, "name", $app->request->params('name'));
        $emoji->emoji_char = Authenticator::checkParamValue($app, "emoji_char", $app->request->params('emoji_char'));
        $emoji->category = Authenticator::checkParamValue($app, "category", $app->request->params('category'));
        $emoji->keywords = Authenticator::checkParamValue($app, "keywords", $app->request->params('keywords'));
        $emoji->created_by = Authenticator::checkParamValue($app, "created_by", $app->request->params('created_by'));

        $rows = $emoji->save();

        if($rows > 0) {
            OutputFormatter::formatOutput($app, 200, 'Emoji successfully updated.');
        }
        else {
            OutputFormatter::formatOutput($app, 401, 'Emoji full update failed!');
        }
    }

    /**
     * This function updates some fields of an empji record in the database
     * @param  Slim   $app      [Slim instance]
     * @param  $id              [the id of the emoji in the emojis table]
     */
    public static function patchEmoji(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        Authenticator::authenticate($app);

        $emoji = Emoji::findById($id);

        if(is_string($emoji)) {
            OutputFormatter::formatOutput($app, 404, $emoji);
        }

        $params = $app->request->patch();

        foreach ($params as $key => $value) {
            $emoji->$key = $value;
        }

        $rows = $emoji->save();

        if($rows > 0) {
            OutputFormatter::formatOutput($app, 200, 'Emoji successfully updated.');
        }
        else {
            OutputFormatter::formatOutput($app, 400, 'Emoji partial update failed!');
        }
    }

    /**
     * This method is used to delete an emoji from the database
     * @param  Slim   $app      [Slim instance]
     * @param  $id              [the id of the emoji in the emojis table]
     * @return json encoded string
     */
    public static function deleteEmoji(Slim $app, $id)
    {
        $app->response->headers->set('Content-Type', 'application/json');

        Authenticator::authenticate($app);

        $rows = Emoji::destroyById($id);

        if(is_string($rows)) {
            OutputFormatter::formatOutput($app, 404, $rows);
        }

        if($rows > 0) {
            OutputFormatter::formatOutput($app, 200, "Emoji with ID $id deleted successfully.");
        }
        else {
            OutputFormatter::formatOutput($app, 400, "Failed to delete Emoji with ID $id.");
        }
    }
}