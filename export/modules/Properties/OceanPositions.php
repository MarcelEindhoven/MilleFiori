<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class OceanPositions implements \ArrayAccess {
    static public function CreateFromPlayerProperties($data) : OceanPositions {
        $object = new OceanPositions();
        return $object->setData($data);
    }

    public function setData($data) : OceanPositions {
        $this->data = $data;
        return $this;
    }

    public function offsetSet(mixed $player_id, mixed $position): void {
        $this->data[$player_id][Ocean::KEY_PLAYER_POSITION] = $position;
    }

    public function offsetGet(mixed $player_id): mixed {
        return $this->data[$player_id][Ocean::KEY_PLAYER_POSITION];
    }

    public function offsetExists(mixed $player_id): bool {return $this->data->offsetExists($player_id);}
    public function offsetUnset(mixed $player_id): void { $this->data->offsetUnset($player_id);}
}

?>
