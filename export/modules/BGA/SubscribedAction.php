<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/Action.php');

class SubscribedAction extends Action {
    public function setEventEmitter($event_handler) : SubscribedAction {
        $this->event_handler = $event_handler;
        return $this;
    }

    public function nextState() {
        parent::nextState();
    }
}
?>
