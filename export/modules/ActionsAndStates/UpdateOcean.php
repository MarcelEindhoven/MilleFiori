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

class UpdateOcean extends Ocean {
    static public function create($player_id, $data): UpdateOcean {
        $object = new UpdateOcean();
        $object->player_id = $player_id;
        return $object->setData($data);
    }

    public function setData($data) : UpdateOcean {
        $this->data = $data;
        return $this;
    }

    public function getPlayerID(): int {
        return $this->player_id;
    }
}

?>
