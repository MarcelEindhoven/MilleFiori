<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../Fields.php');

class CurrentFields extends Fields {

    public static function create($storage) : CurrentFields {
        $object = new CurrentFields();
        return $object->setDatabase($storage);
    }

    public function setDatabase($storage) : CurrentFields {
        $this->storage = $storage;
        return $this;
    }

    public function getFields() : array {
        return $this->storage->getBucket(Fields::BUCKET_NAME, Fields::BUCKET_KEYS);
    }
}

?>
