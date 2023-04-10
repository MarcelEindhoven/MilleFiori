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

    protected function getQueryStringField(array $values_field) : string {
        $values_query_strings_field = [];
        foreach ($values_field as $value) {
            $values_query_strings_field[] = "'$value'";
        }

        return "(" . implode(',', $values_query_strings_field) .")";
    }

    protected function getQueryStringAllValues(array $values) : string {
        $values_query_strings = [];
        foreach ($values as $values_field) {
            $values_query_strings[] = $this->getQueryStringField($values_field);
        }
        return '' . implode(',', $values_query_strings);
    }

    public function createBucket(string $bucket_name, array $bucket_fields, array $initial_values) {
        $field_ids_query_string = '' . implode(',', $bucket_fields);
        $initial_values_query_string = $this->getQueryStringAllValues($initial_values);
        $sql = "INSERT INTO $bucket_name ($field_ids_query_string) VALUES $initial_values_query_string";
        $this->sql_database->query($sql);
    }
}
?>
