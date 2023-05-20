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
    protected bool $select_extra_card = false;

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

    public function setEventEmitter($event_handler) : ActionPlayerSelectsField {
        $this->event_handler = $event_handler;
        return $this;
    }

    public function setCurrentPlayerID($player_id) : ActionPlayerSelectsField {
        $this->player_id = $player_id;
        return $this;
    }

    public function selectExtraCard($event) {
        $this->select_extra_card = true;
    }

    public function execute() : ActionPlayerSelectsField {
        $this->notify_handler->notifyPlayer($this->player_id, 'selectableFields', '', ['selectableFields' => []]);

        $this->cards_handler->emptyPlayedHand();

        $this->event_handler->on('SelectExtraCard', [$this, 'selectExtraCard']);

        // Get reward
        // Execute effect on data handler?
        // Process points
        // Process extra card

        return $this;
    }
    public function nextState() {
        $this->gamestate->nextState($this->select_extra_card ? 'selectExtraCard' : 'turnEnded');
    }
}

?>
