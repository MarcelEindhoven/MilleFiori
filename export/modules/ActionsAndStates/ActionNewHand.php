<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */


class ActionNewHand {

    public static function create($data) : ActionNewHand {
        $object = new ActionNewHand();
        return $object->setData($data)->setNumberCardsNewHand(2);
    }

    public function setGameState($gamestate) : ActionNewHand {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function setData($data) : ActionNewHand {
        $this->data = $data;
        return $this;
    }

    public function setCardsHandler($cards_handler) : ActionNewHand {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setNumberCardsNewHand($number_cards) : ActionNewHand {
        $this->number_cards = $number_cards;
        return $this;
    }

    public function setCardSelectionSimultaneous($is_card_selection_simultaneous) : ActionNewHand {
        $this->is_card_selection_simultaneous = $is_card_selection_simultaneous;
        return $this;
    }

    public function execute() : ActionNewHand {
        foreach ($this->data->getPlayerRobotIDs() as $player_id) {
            $this->cards_handler->moveHandToSideboard($player_id);
            $this->cards_handler->dealNewHand($player_id, $this->number_cards);
        }

        return $this;
    }

    public function nextState() : ActionNewHand {
        if ($this->is_card_selection_simultaneous) {
            $this->gamestate->nextState('selectCardMultipleActivePlayers');
        } else {
            $this->gamestate->nextState('selectCardSingleActivePlayer');
        }

        return $this;
    }
}

?>
