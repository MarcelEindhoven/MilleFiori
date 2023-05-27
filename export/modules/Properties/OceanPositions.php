<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class OceanPositions extends \ArrayObject {
    static public function CreateFromPlayerProperties($data) : OceanPositions {
        $positions = [];
        foreach($data as $player_id => $array) {
            $positions[$player_id] = $array[Ocean::KEY_PLAYER_POSITION];
        }
        return new OceanPositions($positions);
    }

    public function setEventEmitter($event_handler) : OceanPositions {
        $this->event_handler = $event_handler;
        return $this;
    }
}

?>
