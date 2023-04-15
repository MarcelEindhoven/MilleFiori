<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class Houses {
    const KEY_CATEGORY = 'houses';

    const FIELD_WIDTH = 2.86;
    const FIELD_HEIGHT = 4;
    const BOTTOM = 44.5;
    const RIGHT_EDGE = 52;
    const NUMBER_FIELDS = 20;
    const TOP_MARGIN = 0.7;

    const POINTS_PER_POSITION = [2, 4, 3, 5, 1, 4, 2, 3, 1, 5, 10, 2, 3, 4, 5, 10, 1, 5, 4, 3];

    public static function create($event_handler): Houses {
        $object = new Houses();
        return $object;
    }

    public function getCategory() {
        return Houses::KEY_CATEGORY;
    }

    public function getReward($player, $chosen_id) : array {
        $points = Houses::POINTS_PER_POSITION[ $chosen_id];
        $extra_card = false;
        return ['points' => $points, 'extra_card' => $extra_card];
    }

    public function getSelectableFieldIDs($player, int $card_type) : array {
        return [];
    }

    public static function generateFields() {
        $fields = array();
        for ($i = 1; $i <= Houses::NUMBER_FIELDS; ++$i) {
            $fields[] = array (
                'ID' => Houses::NUMBER_FIELDS - $i,
                'LEFT' => Houses::RIGHT_EDGE - Houses::FIELD_HEIGHT,
                'TOP' => Houses::BOTTOM - Houses::FIELD_WIDTH * $i
            );
        }
        foreach($fields as & $field) {
            $margin = $field['TOP'];
            if ($margin < Houses::TOP_MARGIN) {
                $field['LEFT'] -= (Houses::TOP_MARGIN - $margin) * 1.1;
                $field['TOP'] = Houses::TOP_MARGIN;
            }
        }
        return $fields;
    }
}

?>