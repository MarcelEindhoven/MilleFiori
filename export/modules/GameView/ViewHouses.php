<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class ViewHouses extends Houses {
    const KEY_CATEGORY = 'houses';

    const FIELD_WIDTH = 2.86;
    const FIELD_HEIGHT = 4;
    const BOTTOM = 44.5;
    const RIGHT_EDGE = 52;
    const NUMBER_FIELDS = 20;
    const TOP_MARGIN = 0.7;

    public static function generateFields() {
        $fields = array();
        for ($i = 1; $i <= ViewHouses::NUMBER_FIELDS; ++$i) {
            $fields[] = array (
                'ID' => ViewHouses::NUMBER_FIELDS - $i,
                'LEFT' => ViewHouses::RIGHT_EDGE - ViewHouses::FIELD_HEIGHT,
                'TOP' => ViewHouses::BOTTOM - ViewHouses::FIELD_WIDTH * $i
            );
        }
        foreach($fields as & $field) {
            $margin = $field['TOP'];
            if ($margin < ViewHouses::TOP_MARGIN) {
                $field['LEFT'] -= (ViewHouses::TOP_MARGIN - $margin) * 1.1;
                $field['TOP'] = ViewHouses::TOP_MARGIN;
            }
        }
        return $fields;
    }
}

?>
