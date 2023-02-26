<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class Fields {
    const FIELD_PREFIX = 'field_';
    const FIELD_SEPARATOR = '_';

    static public function completeIDs(string $category, array $ids) : array {
        $completeIDs = [];
        foreach ($ids as $id) {
            $completeIDs[] = Fields::completeID($category, $id);
        }
        return $completeIDs;
    }

    static public function completeID(string $category, string $id) : string {
        return Fields::FIELD_PREFIX . $category . Fields::FIELD_SEPARATOR . $id;
    }
}