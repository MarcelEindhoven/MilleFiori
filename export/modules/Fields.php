<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class Fields {
    const BUCKET_KEYS = [Fields::FIELD_ID_NAME, Fields::PLAYER_ID_NAME];
    const FIELD_PREFIX = 'field_';
    const FIELD_SEPARATOR = '_';

    const NOT_OCCUPIED = -1;
    const BUCKET_NAME = 'field';
    const FIELD_ID_NAME = 'field_id';
    const PLAYER_ID_NAME = 'player_id';

    static public function create($storage) : Fields {
        $object = new Fields();
        return $object->setDatabase($storage);
    }

    public function setDatabase($storage) : Fields {
        $this->storage = $storage;
        return $this;
    }

    public function occupyField($field_id, $player_id) {
        $this->storage->updateValueForField(Fields::BUCKET_NAME, Fields::PLAYER_ID_NAME, $player_id, Fields::FIELD_ID_NAME, $field_id);
    }

    static public function completeIDs(string $category, array $ids) : array {
        $completeIDs = [];
        foreach ($ids as $id) {
            $completeIDs[] = Fields::completeID($category, $id);
        }
        return $completeIDs;
    }

    static public function completeID(string $category, string $id) : string {
        return Fields::FIELD_PREFIX . $category . Fields::FIELD_SEPARATOR . $id;
    }

    public static function getID(string $field_id): string {
        return explode(Fields::FIELD_SEPARATOR, $field_id)[2];
    }
}