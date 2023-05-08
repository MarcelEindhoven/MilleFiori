<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/DatabaseInterface.php');

class UpdateStorage {

    static public function create($sql_database) : UpdateStorage {
        $object = new UpdateStorage();
        return $object->setDatabase($sql_database);
    }

    public function setDatabase($sql_database) : UpdateStorage {
        $this->sql_database = $sql_database;
        return $this;
    }

    public function updateValueForField($bucket_name, $field_name_value, $value, $field_name_selector, $value_selector) {
        $this->sql_database->query("UPDATE $bucket_name SET $field_name_value=$value WHERE $field_name_selector=$value_selector");
    }
}
?>
