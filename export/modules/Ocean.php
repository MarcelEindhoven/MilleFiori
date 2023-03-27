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
    const FIELD_WIDTH = 2.72;
    const FIELD_HEIGHT = 4;
    const BOTTOM_TOP = 52-4;
    const RIGHT_EDGE = 52;
    const NUMBER_FIELDS = 21;
    const LEFT_MARGIN = 0.7;
    const QUERY_PLAYER = "SELECT player_id id, player_no number, player_score score, player_color color, ocean_position ocean_position FROM player";
    const QUERY_WHERE = " WHERE player_id=";
    const UPDATE_OCEAN_POSITION = "UPDATE player SET ocean_position=";
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

    protected array $playerPositions = array();

    public static function create($properties) : Ocean {
        $ocean = new Ocean();
        return $ocean->setDatabase($properties)->initialiseFromDatabase();
    }

    public static function getTooltips() {
        return Ocean::PLACES_PER_CARD;
    }

    public function setDatabase($properties) : Ocean {
        $this->properties = $properties;
        return $this;
    }

    public function getReward($player, $chosen_id) : array {
        $points = 0;
        $extra_card = false;
        if ($chosen_id != $this->getPlayerPosition($player)) {
            $points = Ocean::POINTS_PER_POSITION[$chosen_id];
            $extra_card = true;
        }
        return ['points' => $points, 'extra_card' => $extra_card];
    }

    public function initialiseFromDatabase() : Ocean {
        $list = $this->properties->getPropertiesPlayersPlusRobots();
        foreach ($list as $player_id => $player) {
            $this->playerPositions[$player[Ocean::KEY_PLAYER_ID]] = $player[Ocean::KEY_PLAYER_POSITION];
        }
        return $this;
    }

    public function getSelectableFields($player, int $card_id) : array {
        return [$this->getNextPlayerPosition($player, $card_id)];
    }

    public static function generateFields() {
        $fields = array();
        for ($i = 1; $i <= Ocean::NUMBER_FIELDS; ++$i) {
            $fields[] = array (
                'ID' => Ocean::NUMBER_FIELDS - $i,
                'LEFT' => Ocean::RIGHT_EDGE - Ocean::FIELD_WIDTH * $i,
                'TOP' => Ocean::BOTTOM_TOP
            );
        }
        foreach($fields as & $field) {
            $margin = $field['LEFT'];
            if ($margin < Ocean::LEFT_MARGIN) {
                $field['LEFT'] = Ocean::LEFT_MARGIN;
                $field['TOP'] -= (Ocean::LEFT_MARGIN - $margin) * 1.1;
            }
        }
        return $fields;
    }

    public function getPlayerPosition($player) {
        return $this->playerPositions[$player];
    }

    public function setPlayerPosition($player, int $places) : Ocean {
        if ($places > $this->getPlayerPosition($player)) {
            $this->playerPositions[$player] = $places;
            $this->properties->setOceanPosition($player, $places);
        }

        return $this;
    }

    private function getNextPlayerPosition($player, int $card_id) : int {
        $position = $this->getPlayerPosition($player);

        $position += Ocean::PLACES_PER_CARD[$card_id];

        if ($position >= Ocean::NUMBER_FIELDS) {
            $position = Ocean::NUMBER_FIELDS - 1;
        }
        
        return $position;
    }
}

?>
