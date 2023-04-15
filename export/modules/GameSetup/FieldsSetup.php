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

class FieldsSetup {
    const NOT_OCCUPIED = -1;
    const BUCKET_NAME = 'field';
    const FIELD_ID_NAME = 'field_id';
    const PLAYER_ID_NAME = 'player_id';

    static public function create($storage) : FieldsSetup {
        $object = new FieldsSetup();
        return $object->setDatabase($storage);
    }

    public function setDatabase($storage) : FieldsSetup {
        $this->storage = $storage;
        return $this;
    }

    public function setup(array $field_ids) {
        $initial_values = [];
        foreach ($field_ids as $field_id) {
            $initial_values[] = [$field_id, Fields::NOT_OCCUPIED];
        }
        $this->storage->createBucket(Fields::BUCKET_NAME, [Fields::FIELD_ID_NAME, Fields::PLAYER_ID_NAME], $initial_values);
    }
}

?>
