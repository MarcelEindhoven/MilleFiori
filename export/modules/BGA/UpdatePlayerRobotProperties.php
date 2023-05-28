<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class UpdateSpecificProperty extends \ArrayObject {
    public function setEventEmitter($event_handler) : UpdateSpecificProperty {
        $this->event_handler = $event_handler;
        return $this;
    }

    public function setPlayerID($player_id) : UpdateSpecificProperty {
        $this->player_id = $player_id;
        return $this;
    }

    public function isRobotProperty() : bool {
        return $this->player_id < 10;
    }

    public function offsetSet(mixed $property_name, mixed $property_value): void {
        parent::offsetSet($property_name, $property_value);

        $event = [
            UpdateStorage::EVENT_KEY_BUCKET => $this->isRobotProperty() ? UpdatePlayerRobotProperties::EVENT_KEY_BUCKET_ROBOT : UpdatePlayerRobotProperties::EVENT_KEY_BUCKET_PLAYER,
            UpdateStorage::EVENT_KEY_NAME_VALUE => $property_name,
            UpdateStorage::EVENT_KEY_UPDATED_VALUE => $property_value,
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => UpdatePlayerRobotProperties::EVENT_KEY_NAME_SELECTOR,
            UpdateStorage::EVENT_KEY_SELECTED => $this->player_id
        ];
        $this->event_handler->emit(UpdateStorage::EVENT_NAME, $event);
    }
}

class UpdatePlayerRobotProperties extends \ArrayObject {
    const EVENT_KEY_BUCKET_ROBOT = 'robot';
    const EVENT_KEY_BUCKET_PLAYER = 'player';
    const EVENT_KEY_NAME_SELECTOR = 'player_id';

    public function __construct(array|object $array = [], int $flags = 0, string $iteratorClass = ArrayIterator::class) {
        parent::__construct([]);
        foreach($array as $player_id => $player_properties) {
            $this[$player_id] = new UpdateSpecificProperty($player_properties);
        }
    }

    public function setEventEmitter($event_handler) : UpdatePlayerRobotProperties {
        foreach($this->getIterator() as $player_id => $player_properties) {
            $player_properties->setPlayerID($player_id);
            $player_properties->setEventEmitter($event_handler);
        }

        $this->event_handler = $event_handler;

        return $this;
    }
}
?>
