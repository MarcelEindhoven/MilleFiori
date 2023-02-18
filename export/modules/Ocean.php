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
    const KEY_CATEGORY = 'OCEAN';
    const FIELD_WIDTH = 2.7;
    const FIELD_HEIGHT = 4;
    const BOTTOM_TOP = 52-4;
    const RIGHT_EDGE = 52;
    const NUMBER_FIELDS = 21;

    protected array $playerPositions = array();

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
            if ($margin < 0) {
                $field['LEFT'] = 0;
                $field['TOP'] += $margin * Ocean::FIELD_HEIGHT / Ocean::FIELD_WIDTH;
            }
        }
        return $fields;
    }

    public function getPlayerPosition($player) {
        $this->initialisePlayerPositionIfNeeded($player);

        return $this->playerPositions[$player];
    }

    public function advancePlayerPosition($player, int $places) : Ocean {
        $this->initialisePlayerPositionIfNeeded($player);

        $this->playerPositions[$player] += $places;

        if ($this->playerPositions[$player] > Ocean::NUMBER_FIELDS) {
            $this->playerPositions[$player] = Ocean::NUMBER_FIELDS;
        }

        return $this;
    }

    private function initialisePlayerPositionIfNeeded($player) {
        if (! isset($this->playerPositions[$player])) {
            $this->playerPositions[$player] = 0;
        }

        return $this;
    }
}

?>
