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

class ActionRobotSelectsCard extends \NieuwenhovenGames\BGA\Action {

    public static function create($gamestate) : ActionRobotSelectsCard {
        $object = new ActionRobotSelectsCard();
        return $object->setGameState($gamestate);
    }

    public function setCardsHandler($cards_handler) : ActionRobotSelectsCard {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setRobot($robot) : ActionRobotSelectsCard {
        $this->robot = $robot;
        return $this;
    }

    public function execute() : ActionRobotSelectsCard {
        $card = $this->robot->selectCard();

        $this->cards_handler->moveFromHandToSelected($card[CurrentData::CARD_KEY_ID], $this->robot->getPlayerID());

        return $this;
    }
}

?>
