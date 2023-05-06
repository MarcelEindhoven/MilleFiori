<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class ActionPlayerSelectsField {

    public static function create($gamestate) : ActionPlayerSelectsField {
        $object = new ActionPlayerSelectsField();
        return $object->setGameState($gamestate);
    }

    public function setGameState($gamestate) : ActionPlayerSelectsField {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function setCardsHandler($cards_handler) : ActionPlayerSelectsField {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setDataHandler($data_handler) : ActionPlayerSelectsField {
        $this->data_handler = $data_handler;
        return $this;
    }

    public function setNotifyHandler($notify_handler) : ActionPlayerSelectsField {
        $this->notify_handler = $notify_handler;
        return $this;
    }

    public function setCurrentPlayerID($player_id) : ActionPlayerSelectsField {
        $this->player_id = $player_id;
        return $this;
    }

    public function execute() : ActionPlayerSelectsField {
        $this->notify_handler->notifyPlayer($this->player_id, 'selectableFields', '', ['selectableFields' => []]);

        return $this;
    }
}

?>
