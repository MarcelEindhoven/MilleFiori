<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../Robot.php');

class ActionRobotsSelectCard {

    public static function create($data) : ActionRobotsSelectCard {
        $object = new ActionRobotsSelectCard();
        return $object->setData($data);
    }

    public function setGameState($gamestate) : ActionRobotsSelectCard {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function setData($data) : ActionRobotsSelectCard {
        $this->data = $data;
        return $this;
    }

    public function setCardsHandler($cards_handler) : ActionRobotsSelectCard {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function execute() {
        foreach ($this->data->getRobotIDs() as $robot_id) {
            $card_id = Robot::create($robot_id, $this->data)->selectCard();
            $this->cards_handler->moveFromHandToSelected($card_id, $robot_id);
        }
    }

    public function nextState() {
        $this->gamestate->nextState();
    }
}

?>
