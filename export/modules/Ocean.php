<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class Ocean {
    const KEY_CATEGORY = 'ocean';
    const KEY_PLAYER_POSITION = 'ocean_position';
    const KEY_PLAYER_ID = 'id';
    const NUMBER_FIELDS = 21;

    const PLACES_PER_CARD = [1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5
    , 1, 1, 1, 2, 2, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 0
    , 1, 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5
    , 1, 2, 3, 5, 1, 2, 3, 5, 1, 2, 3, 4
    , 1, 2, 3, 4, 1, 2, 3, 4, 1, 2, 3, 5
    , 1, 2, 3, 3, 3, 4, 5
    , 1, 1, 2, 3, 4, 5, 5
    , 1, 2, 2, 3, 4, 4, 5
    , 1, 1, 2, 2, 3, 3, 4, 4, 5];
    const POINTS_PER_POSITION = [0, 1, 1, 1, 3, 1, 10, 0, 1, 5, 1, 1, 10, 10, 1, 5, 0, 1, 1, 1, 10];
    const EXTRA_CARD_PER_POSITION = [false, false, false, false, false, false, false, true, false, false, false, false, false, false, false, false, true, false, false, false, true];

    public function setData($player_robot_data) : Ocean {
        $this->player_robot_data = $player_robot_data;
        return $this;
    }

    protected function getPlayerPosition($player) {
        return $this->player_robot_data[$player][Ocean::KEY_PLAYER_POSITION];
    }

    protected function getNextPlayerPosition($player, int $card_type) : int {
        $position = $this->getPlayerPosition($player);

        $position += Ocean::PLACES_PER_CARD[$card_type];

        if ($position >= Ocean::NUMBER_FIELDS) {
            $position = Ocean::NUMBER_FIELDS - 1;
        }
        
        return $position;
    }

    // Deprecated
    protected array $playerPositions = array();

    public function setDatabase($properties) : Ocean {
        $this->properties = $properties;
        return $this;
    }

    public function getCategoryID() {
        return Ocean::KEY_CATEGORY;
    }

    public function initialiseFromDatabase() : Ocean {
        if (! $this->properties) {return $this;}

        $list = $this->properties->getPropertiesPlayersPlusRobots();
        foreach ($list as $player_id => $player) {
            $this->playerPositions[$player[Ocean::KEY_PLAYER_ID]] = $player[Ocean::KEY_PLAYER_POSITION];
        }
        return $this;
    }

    public function getSelectableFieldIDs($player, int $card_id) : array {
        return [$this->getNextPlayerPosition($player, $card_id)];
    }
}

?>
