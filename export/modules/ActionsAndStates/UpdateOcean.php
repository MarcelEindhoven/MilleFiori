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
    static public function create($data): UpdateOcean {
        $object = new UpdateOcean();
        $object->setOceanPositions($data);
        return $object;
    }

    public function setRewardHandler($reward_handler) : UpdateOcean {
        $this->reward_handler = $reward_handler;
        return $this;
    }

    public function setOceanPositions($properties) : UpdateOcean {
        $this->ocean_positions = $properties;
        return $this;
    }

    protected function getPlayerPosition($player_id) {
        return $this->ocean_positions[$player_id];
    }

    public function playerSelectsField($player_id, $chosen_field_id) {
        $position = Fields::getID($chosen_field_id);
        if ($position != $this->getPlayerPosition($player_id)) {
            $this->playerSelectsNewPosition($player_id, $position);
        }
    }

    private function playerSelectsNewPosition($player_id, $position) {
        $this->ocean_positions[$player_id] = $position;

        if (Ocean::POINTS_PER_POSITION[$position] > 0) {
            $this->reward_handler->gainedPoints($player_id, Ocean::POINTS_PER_POSITION[$position]);
        }

        if (Ocean::EXTRA_CARD_PER_POSITION[$position]) {
            $this->reward_handler->gainedAdditionalReward($player_id, 'select_extra_card');
        }
    }
}

?>
