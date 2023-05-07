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

class Storage {

    static public function create($sql_database) : Storage {
        $object = new Storage();
        return $object->setDatabase($sql_database);
    }

    public function setDatabase($sql_database) : Storage {
        $this->sql_database = $sql_database;
        return $this;
    }

    public function getBucket(string $bucket_name, array $bucket_fields, string $prefix = '') {
        // Why a prefix is needed: https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        $field_names_query_strings = [];
        foreach ($bucket_fields as $field_name) {
            $field_name_with_optional_prefix = $prefix . $field_name;
            $field_names_query_strings[] = "$field_name_with_optional_prefix $field_name";
        }

        $field_names_query = ''  . implode(', ', $field_names_query_strings);

        return $this->sql_database->getCollection("SELECT $field_names_query FROM $bucket_name");
    }

    public function updateValueForField($bucket_name, $field_name_value, $value, $field_name_selector, $value_selector) {
        $this->sql_database->query("UPDATE $bucket_name SET $field_name_value=$value WHERE $field_name_selector=$value_selector");
    }
}
?>
