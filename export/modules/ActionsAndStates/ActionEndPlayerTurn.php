<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class ActionEndPlayerTurn {

    public static function create($gamestate) : ActionEndPlayerTurn {
        $object = new ActionEndPlayerTurn();
        return $object->setGameState($gamestate);
    }

    public function setGameState($gamestate) : ActionEndPlayerTurn {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function setCardsHandler($cards_handler) : ActionEndPlayerTurn {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setCardSelectionSimultaneous($is_card_selection_simultaneous) : ActionEndPlayerTurn {
        $this->is_card_selection_simultaneous = $is_card_selection_simultaneous;
        return $this;
    }

    public function execute() : ActionEndPlayerTurn {
        return $this;
    }

    public function nextState() {
        $this->gamestate->nextState('turn' . 'Ended');
    }
}

?>
