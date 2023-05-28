<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class PlayerProperty implements \ArrayAccess {
    static public function CreateFromPlayerProperties($property_name, $data) : PlayerProperty {
        $object = new PlayerProperty();
        return $object->setData($data)->setPropertyName($property_name);
    }

    public function setData($data) : PlayerProperty {
        $this->data = $data;
        return $this;
    }

    public function setPropertyName($property_name) : PlayerProperty {
        $this->property_name = $property_name;
        return $this;
    }

    public function offsetSet(mixed $player_id, mixed $position): void {
        $this->data[$player_id][$this->property_name] = $position;
    }

    public function offsetGet(mixed $player_id): mixed {
        return $this->data[$player_id][$this->property_name];
    }

    public function offsetExists(mixed $player_id): bool {return $this->data->offsetExists($player_id);}
    public function offsetUnset(mixed $player_id): void { $this->data->offsetUnset($player_id);}
}

?>
