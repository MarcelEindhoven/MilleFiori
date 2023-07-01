<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Each robot selects a card
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../ActionsAndStates/Robot.php');
include_once(__DIR__.'/ActionSelectsCard.php');

class ActionRobotsSelectCard extends ActionSelectsCard {

    public static function create($gamestate) : ActionRobotsSelectCard {
        return new ActionRobotsSelectCard($gamestate);
    }

    public function setRobotHandler($robot_handler) : ActionRobotsSelectCard {
        $this->robot_handler = $robot_handler;
        return $this;
    }

    public function execute() : ActionRobotsSelectCard {
        foreach ($this->robot_handler->getRobots() as $robot) {
            $card = $robot->selectCard();

            $this->setPlayerAndCard($robot->getPlayerID(), $card[CurrentData::CARD_KEY_ID]);
            $this->processSelectedCard();
        }

        return $this;
    }
}

?>
