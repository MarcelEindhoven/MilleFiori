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

    public static function create($position_data) : CurrentOcean {
        $object = new CurrentOcean();
        return $object->setData($position_data);
    }

    public function setData($position_data) : CurrentOcean {
        $this->position_data = $position_data;
        return $this;
    }

    public function getSelectableFieldIDs($player, int $card_id) : array {
        return [$this->getNextPlayerPosition($player, $card_id)];
    }

    public function getPlayerPosition($player) {
        return $this->position_data[$player][Ocean::KEY_PLAYER_POSITION];
    }
}

?>
