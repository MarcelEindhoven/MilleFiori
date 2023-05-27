<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Responsible for Ocean category
 * - Reward for selecting a new ship position
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../Ocean.php');

class UpdateOcean extends Ocean {
    static public function create($player_id, $data): UpdateOcean {
        $object = new UpdateOcean();
        $object->player_id = $player_id;
        $object->ocean_positions = $data;
        return $object;
    }

    public function setEventEmitter($event_handler) : UpdateOcean {
        $this->event_handler = $event_handler;
        return $this;
    }

    public function getPlayerID(): int {
        return $this->player_id;
    }

    protected function getPlayerPosition($player) {
        return $this->ocean_positions[$player];
    }

    public function PlayerSelectsField($player_id, $chosen_field_id) {
        $position = Fields::getID($chosen_field_id);
        if ($position != $this->getPlayerPosition($player_id)) {
            $this->PlayerSelectsNewPosition($player_id, $position);
        }
    }

    private function PlayerSelectsNewPosition($player_id, $position) {
        if (Ocean::POINTS_PER_POSITION[$position] > 0) {
            $this->event_handler->emit('Points', ['player_id' => $player_id, 'points' => Ocean::POINTS_PER_POSITION[$position]]);
        }
        if (Ocean::EXTRA_CARD_PER_POSITION[$position]) {
            $this->event_handler->emit('SelectExtraCard', []);
        }
    }
}

?>
