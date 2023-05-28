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

    public function offsetSet(mixed $property_name, mixed $property_value): void {
        parent::offsetSet($property_name, $property_value);
        $event_position = [];
        $this->event_handler->emit('PlayerPropertyUpdated', $event_position);
    }
}

class UpdatePlayerRobotProperties extends \ArrayObject {
    public function __construct(array|object $array = [], int $flags = 0, string $iteratorClass = ArrayIterator::class) {
        parent::__construct([]);
        foreach($array as $player_id => $player_properties) {
            $this[$player_id] = new UpdateSpecificProperty($player_properties);
        }
    }

    public function setEventEmitter($event_handler) : UpdatePlayerRobotProperties {
        foreach($this->getIterator() as $player_id => $player_properties) {
            $player_properties->setEventEmitter($event_handler);
        }

        $this->event_handler = $event_handler;

        return $this;
    }
}
?>
