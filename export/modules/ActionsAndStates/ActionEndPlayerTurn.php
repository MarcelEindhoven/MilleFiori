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

    protected function hasRoundEnded(): bool {
        return !(($this->cards_handler->getNumberPlayerCards() + $this->cards_handler->getNumberSelectedCards()) % 4);
    }

    protected function hasHandEnded(): bool {
        return $this->cards_handler->getNumberPlayerCards() <= 4;
    }

    protected function hasGameEnded(): bool {
        return $this->cards_handler->getNumberDeckCards() < 20;
    }

    public function nextState() {
        $postfix = '';

        if (! $this->hasRoundEnded()) {
            $what = 'turn';
        } else if (! $this->hasHandEnded()) {
            $what = 'round';
            $postfix = $this->is_card_selection_simultaneous ? 'MultipleActivePlayers' : 'SingleActivePlayer';
        } else if (! $this->hasGameEnded()) {
            $what = 'hand';
        } else {
            $what = 'game';
        }
        $this->gamestate->nextState($what . 'Ended' . $postfix);
    }
}

?>
