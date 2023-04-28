<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/CurrentData/CurrentData.php');

class Robot {
    static public function create($player_id, $data): Robot {
        $object = new Robot();
        $object->player_id = $player_id;
        return $object->setData($data);
    }

    public function setData($data) : Robot {
        $this->data = $data;
        return $this;
    }

    public function getPlayerID(): int {
        return $this->player_id;
    }

    public function selectCard() {
        $cards = $this->data->getHand($this->player_id);
        $IDs = array_column($cards, CurrentData::CARD_KEY_ID);
        if (! $IDs) {
            return;
        }
        // For now, any will do
        return array_shift($IDs);
    }

    public function selectField(array $IDs) {
        if (! $IDs) {
            return;
        }
        // For now, any will do
        return array_shift($IDs);
    }
}

?>
