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

    public function execute() : ActionEndPlayerTurn {
        return $this;
    }

    protected function hasRoundEnded(): bool {
        return !(($this->cards_handler->getNumberPlayerCards() + $this->cards_handler->getNumberSelectedCards()) % 4);
    }

    public function nextState() {
        $what = $this->hasRoundEnded() ? 'round' : 'turn';

        $this->gamestate->nextState($what . 'Ended');
    }
}

?>
