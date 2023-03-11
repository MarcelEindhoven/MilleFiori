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

class Game {
    const NUMBER_CARDS_INCLUDING_START = 110;
    const INDEX_START_CARD = 35;
    static public function getCardDefinitions(): array {
        $cards = array ();
        for ($id = 0;  $id < Game::NUMBER_CARDS_INCLUDING_START; $id++ ) {
            if ($id != Game::INDEX_START_CARD) {
                $cards [] = array ('type' => $id,'type_arg' => 0,'nbr' => 1 );
            }
        }
        return $cards;
    }
}

?>
