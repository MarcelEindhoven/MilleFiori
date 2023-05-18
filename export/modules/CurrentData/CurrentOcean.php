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

    public function getSelectableFieldIDs($player, int $card_id) : array {
        return [$this->getFieldIDForPosition($this->getNextPlayerPosition($player, $card_id))];
    }

    public function getFieldIDForPosition($position) {
        return Fields::completeID($this->getCategoryID(), $position);
    }

    public function getReward($player, $chosen_field_id) : array {
        $position = Fields::getID($chosen_field_id);
        $reward = ['points' => 0, 'extra_card' => false];
        if ($position != $this->getPlayerPosition($player)) {
            $reward['points'] = Ocean::POINTS_PER_POSITION[$position];
            $reward['extra_card'] = Ocean::EXTRA_CARD_PER_POSITION[$position];
        }
        return $reward;
    }

    public function getPlayerPosition($player) {
        return $this->player_robot_data[$player][Ocean::KEY_PLAYER_POSITION];
    }

    public static function getTooltipsCards() {
        return Ocean::PLACES_PER_CARD;
    }
}

?>
