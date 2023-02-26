<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/BGA/DatabaseInterface.php');

class Ocean {
    const KEY_CATEGORY = 'ocean';
    const KEY_PLAYER_POSITION = 'ocean_position';
    const KEY_PLAYER_ID = 'id';
    const FIELD_WIDTH = 2.72;
    const FIELD_HEIGHT = 4;
    const BOTTOM_TOP = 52-4;
    const RIGHT_EDGE = 52;
    const NUMBER_FIELDS = 21;
    const LEFT_MARGIN = 1.3;
    const QUERY_PLAYER = "SELECT player_id id, player_no player_number, player_score score, player_color color, ocean_position ocean_position FROM player";
    const QUERY_WHERE = " WHERE player_id=";
    const UPDATE_OCEAN_POSITION = "UPDATE player SET ocean_position=";

    protected ?\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase = null;

    protected array $playerPositions = array();

    public static function create(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : Ocean {
        $ocean = new Ocean();
        return $ocean->setDatabase($sqlDatabase)->initialiseFromDatabase();
    }

    public function setDatabase(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : Ocean {
        $this->sqlDatabase = $sqlDatabase;
        return $this;
    }

    public function initialiseFromDatabase() : Ocean {
        $list = $this->sqlDatabase->getObjectList(Ocean::QUERY_PLAYER);
        foreach ($list as $player_id => $player) {
            $this->playerPositions[$player[Ocean::KEY_PLAYER_ID]] = $player[Ocean::KEY_PLAYER_POSITION];
        }
        return $this;
    }

    public function getSelectableFields($player, int $places) : array {
        return [$this->getNextPlayerPosition($player, $places)];
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
            $this->sqlDatabase->query(Ocean::UPDATE_OCEAN_POSITION . $places . Ocean::QUERY_WHERE . $player);
        }

        return $this;
    }

    private function getNextPlayerPosition($player, int $places) : int {
        $position = $this->getPlayerPosition($player);

        $position += $places;

        if ($position > Ocean::NUMBER_FIELDS) {
            $position = Ocean::NUMBER_FIELDS;
        }
        
        return $position;
    }
}

?>
