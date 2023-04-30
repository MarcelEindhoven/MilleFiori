<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../ActionsAndStates/Data.php');
include_once(__DIR__.'/../CurrentData/CurrentData.php');

class ActionPlayerWillPlayCard {

    public static function create($gamestate) : ActionPlayerWillPlayCard {
        $object = new ActionPlayerWillPlayCard();
        return $object->setGameState($gamestate);
    }

    public function setGameState($gamestate) : ActionPlayerWillPlayCard {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function setCardsHandler($cards_handler) : ActionPlayerWillPlayCard {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setDataHandler($data_handler) : ActionPlayerWillPlayCard {
        $this->data_handler = $data_handler;
        return $this;
    }

    public function setCurrentPlayerID($player_id) : ActionPlayerWillPlayCard {
        $this->player_id = $player_id;
        return $this;
    }

    public function execute() : ActionPlayerWillPlayCard {
        // Move from selected to played
        $this->cards_handler->playSelectedCard($this->player_id);

        // Activate selectable fields

        return $this;
    }

    public function nextState() {
        $this->gamestate->nextState();
    }
}

?>
