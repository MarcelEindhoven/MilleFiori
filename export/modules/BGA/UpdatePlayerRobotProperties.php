<?php
namespace NieuwenhovenGames\BGA;
/**
 * Design decision: the database is not directly called.
 * Decoupling using an event handler allows usage in simulation
 * without affecting state.
 * After the properties are set during object creation
 * this object is the single source of truth for player/robot properties
 * 
 * // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
 * Who is responsible for the "player_" prefix?
 * The object is initialised with keys that do not contain the prefix
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */
include_once(__DIR__.'/EventEmitter.php');

class PropertiesSinglePlayer extends \ArrayObject {
    public function setEventEmitter($event_handler) : PropertiesSinglePlayer {
        $this->event_handler = $event_handler;
        return $this;
    }

    public function isPlayer() : bool {
        return $this->offsetGet(UpdatePlayerRobotProperties::KEY_IS_PLAYER);
    }

    public function offsetSet($property_name, $property_value): void {
        parent::offsetSet($property_name, $property_value);

        $event = [
            // Event info for updating the database, if database is subscribed
            UpdateStorage::EVENT_KEY_BUCKET => $this->isPlayer() ? UpdatePlayerRobotProperties::PLAYER_BUCKET_NAME : UpdatePlayerRobotProperties::ROBOT_BUCKET_NAME,
            UpdateStorage::EVENT_KEY_NAME_UPDATED_FIELD => UpdatePlayerRobotProperties::PLAYER_KEY_PREFIX . $property_name,
            UpdateStorage::EVENT_KEY_UPDATED_VALUE => $property_value,
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => UpdatePlayerRobotProperties::PLAYER_KEY_PREFIX . UpdatePlayerRobotProperties::KEY_ID,
            UpdateStorage::EVENT_KEY_SELECTED => $this->offsetGet(UpdatePlayerRobotProperties::KEY_ID),
            // Event info to inform the players, may be deprecated
            UpdatePlayerRobotProperties::EVENT_KEY_NAME => $this->offsetGet(UpdatePlayerRobotProperties::KEY_NAME),
            // Message to inform the players
        ];
        $this->event_handler->emit(UpdateStorage::EVENT_NAME, $event);
    }
}

class UpdatePlayerRobotProperties extends \ArrayObject {
    const EVENT_KEY_NAME = 'player_name';

    const PLAYER_BUCKET_NAME = 'player';
    const PLAYER_KEY_PREFIX = 'player_';
    const ROBOT_BUCKET_NAME = 'robot';

    const KEY_SCORE = 'score';
    const KEY_ID = 'id';
    const KEY_NUMBER = 'no';
    const KEY_COLOR = 'color';
    const KEY_NAME = 'name';

    const KEY_IS_PLAYER = 'is_player';

    public function __construct(array $array = [], int $flags = 0, string $iteratorClass = \ArrayIterator::class) {
        parent::__construct([]);
        foreach($array as $player_id => $player_properties) {
            $this[$player_id] = new PropertiesSinglePlayer($player_properties);
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
