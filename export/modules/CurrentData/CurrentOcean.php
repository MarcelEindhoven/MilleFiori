<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Responsible for Ocean category current data
 * - Selectable field IDs within ocean
 * - Tooltips for ocean movement of each card
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

    // Selectable field IDs
    public function getSelectableFieldIDs($player_id, int $card_id) : array {
        return [$this->getFieldIDForPosition($this->getNextPlayerPosition($player_id, $card_id))];
    }

    protected function getFieldIDForPosition($position) {
        return Fields::completeID($this->getCategoryID(), $position);
    }

    // Tooltips
    public static function getTooltipsCards() {
        return Ocean::PLACES_PER_CARD;
    }

    // Deprecated
    public function getReward($player_id, $chosen_field_id) : array {
        $position = Fields::getID($chosen_field_id);
        $reward = ['points' => 0, 'extra_card' => false];
        if ($position != $this->getPlayerPosition($player_id)) {
            $reward['points'] = Ocean::POINTS_PER_POSITION[$position];
            $reward['extra_card'] = Ocean::EXTRA_CARD_PER_POSITION[$position];
        }
        return $reward;
    }
}

?>
