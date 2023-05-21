<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class ViewOcean {
    const KEY_CATEGORY = 'ocean';
    const FIELD_WIDTH = 2.72;
    const FIELD_HEIGHT = 4;
    const BOTTOM_TOP = 52-4;
    const RIGHT_EDGE = 52;
    const NUMBER_FIELDS = 21;
    const LEFT_MARGIN = 0.7;

    public function getCategoryID() {
        return ViewOcean::KEY_CATEGORY;
    }

    public static function generateFields() {
        $fields = array();
        for ($i = 1; $i <= ViewOcean::NUMBER_FIELDS; ++$i) {
            $fields[] = array (
                'ID' => ViewOcean::NUMBER_FIELDS - $i,
                'LEFT' => ViewOcean::RIGHT_EDGE - ViewOcean::FIELD_WIDTH * $i,
                'TOP' => ViewOcean::BOTTOM_TOP
            );
        }
        foreach($fields as & $field) {
            $margin = $field['LEFT'];
            if ($margin < ViewOcean::LEFT_MARGIN) {
                $field['LEFT'] = ViewOcean::LEFT_MARGIN;
                $field['TOP'] -= (ViewOcean::LEFT_MARGIN - $margin) * 1.1;
            }
        }
        return $fields;
    }
}

?>
