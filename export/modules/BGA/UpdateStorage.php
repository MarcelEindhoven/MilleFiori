<?php
namespace NieuwenhovenGames\BGA;
/**
 * When property updated event is received, update database and 
 * propagate bucket specific event to object that notifies players
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/FrameworkInterfaces/Database.php');
include_once(__DIR__.'/EventEmitter.php');

class UpdateStorage {
    protected ?FrameworkInterfaces\Database $sql_database = null;
    protected ?EventEmitter $event_handler = null;

    const EVENT_NAME = 'property_updated';

    const EVENT_KEY_BUCKET = 'bucket_name';
    const EVENT_KEY_NAME_UPDATED_FIELD = 'field_name_value';
    const EVENT_KEY_UPDATED_VALUE = 'new_value';
    const EVENT_KEY_NAME_SELECTOR = 'field_name_selector';
    const EVENT_KEY_SELECTED = 'selected_field';

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
        $this->event_handler->on(UpdateStorage::EVENT_NAME, [$this, 'propertyUpdated']);
        return $this;
    }

    static public function getBucketSpecificEventName(string $bucket_name) {
        return UpdateStorage::EVENT_NAME . '_' . $bucket_name;
    }

    public function propertyUpdated($event) {
        $this->updateValueForField(
            $event[UpdateStorage::EVENT_KEY_BUCKET],
            $event[UpdateStorage::EVENT_KEY_NAME_UPDATED_FIELD],
            $event[UpdateStorage::EVENT_KEY_UPDATED_VALUE],
            $event[UpdateStorage::EVENT_KEY_NAME_SELECTOR],
            $event[UpdateStorage::EVENT_KEY_SELECTED]);

            $event_name = UpdateStorage::getBucketSpecificEventName($event[UpdateStorage::EVENT_KEY_BUCKET]);
            $this->event_handler->emit($event_name, $event);
    }

    public function updateValueForField($bucket_name, $field_name_value, $value, $field_name_selector, $value_selector) {
        $this->sql_database->query("UPDATE $bucket_name SET $field_name_value=$value WHERE $field_name_selector=$value_selector");
    }
}
?>
