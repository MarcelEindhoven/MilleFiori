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
    const EVENT_KEY_BUCKET = 'bucket_name';
    const EVENT_KEY_NAME_VALUE = 'field_name_value';
    const EVENT_KEY_UPDATED_VALUE = 'new_value';
    const EVENT_KEY_NAME_SELECTOR = 'field_name_selector';
    const EVENT_KEY_SELECTED = 'selected_field';
    const EVENT_NAME = 'bucket_updated';

    static public function create($sql_database) : UpdateStorage {
        $object = new UpdateStorage();
        return $object->setDatabase($sql_database);
    }

    public function setDatabase($sql_database) : UpdateStorage {
        $this->sql_database = $sql_database;
        return $this;
    }

    public function setEventEmitter($event_handler) : UpdateStorage {
        $this->event_handler = $event_handler;
        $this->event_handler->on(UpdateStorage::EVENT_NAME, [$this, 'bucketUpdated']);
        return $this;
    }
    public function bucketUpdated($event) {
        $this->updateValueForField(
            $event[UpdateStorage::EVENT_KEY_BUCKET],
            $event[UpdateStorage::EVENT_KEY_NAME_VALUE],
            $event[UpdateStorage::EVENT_KEY_UPDATED_VALUE],
            $event[UpdateStorage::EVENT_KEY_NAME_SELECTOR],
            $event[UpdateStorage::EVENT_KEY_SELECTED]);
    }

    public function updateValueForField($bucket_name, $field_name_value, $value, $field_name_selector, $value_selector) {
        $this->sql_database->query("UPDATE $bucket_name SET $field_name_value=$value WHERE $field_name_selector=$value_selector");

        // Deprecated?
        $event = [
            UpdateStorage::EVENT_KEY_BUCKET => $bucket_name,
            UpdateStorage::EVENT_KEY_NAME_VALUE => $field_name_value,
            UpdateStorage::EVENT_KEY_UPDATED_VALUE => $value,
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => $field_name_selector,
            UpdateStorage::EVENT_KEY_SELECTED => $value_selector];
        $this->event_handler->emit($bucket_name, $event);
    }
}
?>
