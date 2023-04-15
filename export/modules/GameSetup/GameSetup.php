<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Fill the database
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/Storage.php');

class GameSetup {
    const NOT_OCCUPIED = -1;
    const BUCKET_NAME = 'field';
    const FIELD_ID_NAME = 'field_id';
    const PLAYER_ID_NAME = 'player_id';

    public static function create($sqlDatabase) : GameSetup {
        $object = new GameSetup();
        $storage = \NieuwenhovenGames\BGA\Storage::create($sqlDatabase);
        return $object->setDatabase($sqlDatabase);
    }

    public function setDatabase($storage) : GameSetup {
        $this->storage = $storage;
        return $this;
    }

    public function setup() {
    }
}

?>
