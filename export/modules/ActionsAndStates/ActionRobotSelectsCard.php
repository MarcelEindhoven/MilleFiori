<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Current robot selects a card
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../ActionsAndStates/Robot.php');
include_once(__DIR__.'/ActionSelectsCard.php');

class ActionRobotSelectsCard extends ActionSelectsCard {

    public static function create($gamestate) : ActionRobotSelectsCard {
        return new ActionRobotSelectsCard($gamestate);
    }

    public function setRobot($robot) : ActionRobotSelectsCard {
        $this->robot = $robot;
        return $this;
    }

    public function execute() : ActionRobotSelectsCard {
        $card = $this->robot->selectCard();

        $this->setPlayerAndCard($this->robot->getPlayerID(), $card[CurrentData::CARD_KEY_ID]);
        $this->processSelectedCard();

        return $this;
    }
}

?>
