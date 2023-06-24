<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../ActionsAndStates/Robot.php');
include_once(__DIR__.'/../BGA/Action.php');

class ActionRobotsSelectCard extends \NieuwenhovenGames\BGA\Action {

    public static function create($gamestate) : ActionRobotsSelectCard {
        $object = new ActionRobotsSelectCard();
        return $object->setGameState($gamestate);
    }

    public function setCardsHandler($cards_handler) : ActionRobotsSelectCard {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setRobotHandler($robot_handler) : ActionRobotsSelectCard {
        $this->robot_handler = $robot_handler;
        return $this;
    }

    public function execute() : ActionRobotsSelectCard {
        foreach ($this->robot_handler->getRobots() as $robot) {
            $card = $robot->selectCard();
            $this->cards_handler->moveFromHandToSelected($card[CurrentData::CARD_KEY_ID], $robot->getPlayerID());
        }

        return $this;
    }
}

?>
