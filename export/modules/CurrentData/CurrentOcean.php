<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../Ocean.php');

class CurrentOcean extends Ocean {

    public static function create($player_robot_data) : CurrentOcean {
        $object = new CurrentOcean();
        return $object->setData($player_robot_data);
    }

    public function setData($player_robot_data) : CurrentOcean {
        $this->player_robot_data = $player_robot_data;
        return $this;
    }

    public function setEventEmitter($event_handler) : CurrentOcean {
        $this->event_handler = $event_handler;
        return $this;
    }

    public function getSelectableFieldIDs($player_id, int $card_id) : array {
        return [$this->getFieldIDForPosition($this->getNextPlayerPosition($player_id, $card_id))];
    }

    public function getFieldIDForPosition($position) {
        return Fields::completeID($this->getCategoryID(), $position);
    }

    public function getReward($player_id, $chosen_field_id) : array {
        $position = Fields::getID($chosen_field_id);
        $reward = ['points' => 0, 'extra_card' => false];
        if ($position != $this->getPlayerPosition($player_id)) {
            $reward['points'] = Ocean::POINTS_PER_POSITION[$position];
            $reward['extra_card'] = Ocean::EXTRA_CARD_PER_POSITION[$position];
        }
        return $reward;
    }

    public function PlayerSelectsField($player_id, $chosen_field_id) {
        $position = Fields::getID($chosen_field_id);
        if ($position != $this->getPlayerPosition($player_id)) {
            $this->event_handler->emit('Position', ['player_id' => $player_id, 'position' => $position]);
            $reward = $this->getReward($player_id, $chosen_field_id);
            if ($reward['extra_card']) {
                $this->event_handler->emit('SelectExtraCard', []);
            }
        }
    }

    public function getPlayerPosition($player) {
        return $this->player_robot_data[$player][Ocean::KEY_PLAYER_POSITION];
    }

    public static function getTooltipsCards() {
        return Ocean::PLACES_PER_CARD;
    }
}

?>
