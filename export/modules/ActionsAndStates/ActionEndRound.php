<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../BGA/Action.php');

class ActionEndRound extends \NieuwenhovenGames\BGA\Action {

    public static function create($gamestate) : ActionEndRound {
        return new ActionEndRound($gamestate);
    }

    public function setCardsHandler($cards_handler) : ActionEndRound {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setCardSelectionSimultaneous($is_card_selection_simultaneous) : ActionEndRound {
        $this->is_card_selection_simultaneous = $is_card_selection_simultaneous;
        return $this;
    }

    public function execute() : ActionEndRound {
        if ($this->hasHandEnded()) {
            $this->cards_handler->moveHandsToSideboard();
        } else {
            $this->cards_handler->swapHands();
        }
        return $this;
    }

    protected function hasHandEnded(): bool {
        return $this->cards_handler->getNumberPlayerCards() <= 4;
    }

    protected function hasGameEnded(): bool {
        return $this->cards_handler->getNumberDeckCards() < 20;
    }

    public function getTransitionName() : string {
        $postfix = '';

        if (! $this->hasHandEnded()) {
            $what = 'round';
            $postfix = $this->is_card_selection_simultaneous ? 'MultipleActivePlayers' : 'SingleActivePlayer';
        } else if (! $this->hasGameEnded()) {
            $what = 'hand';
        } else {
            $what = 'game';
        }

        return $what . 'Ended' . $postfix;
    }
}

?>
