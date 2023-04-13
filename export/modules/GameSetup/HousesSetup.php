<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * 
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../Houses.php');

class HousesSetup extends Houses {
    public function getAllFieldIDsForOccupation() : array {
        $ids = array();
        for ($i = 0; $i < Houses::NUMBER_FIELDS; ++$i) {
            $ids[] = $i;
        }

        return $ids;
    }
}

?>
