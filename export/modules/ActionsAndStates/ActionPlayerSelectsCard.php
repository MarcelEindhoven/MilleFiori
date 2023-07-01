<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/ActionSelectsCard.php');

class ActionPlayerSelectsCard extends ActionSelectsCard {
    public static function create($gamestate) : ActionPlayerSelectsCard {
        return new ActionPlayerSelectsCard($gamestate);
    }

    public function execute() : ActionPlayerSelectsCard {
        $this->processSelectedCard();

        return $this;
    }
}

?>
