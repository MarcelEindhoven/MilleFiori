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
include_once(__DIR__.'/../CurrentData/CurrentData.php');

class ActionActivatePlayerOrRobot {

    public static function create($gamestate) : ActionActivatePlayerOrRobot {
        $object = new ActionActivatePlayerOrRobot();
        return $object->setGameState($gamestate);
    }

    public function setGameState($gamestate) : ActionActivatePlayerOrRobot {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function setCurrentPlayerOrRobot($current_player_or_robot) : ActionActivatePlayerOrRobot {
        $this->current_player_or_robot = $current_player_or_robot;
        return $this;
    }

    public function setCardSelectionSimultaneous($is_card_selection_simultaneous) : ActionNewHand {
        $this->is_card_selection_simultaneous = $is_card_selection_simultaneous;
        return $this;
    }

    public function execute() : ActionActivatePlayerOrRobot {
        return $this;
    }

    public function nextState() {
        $id = $this->current_player_or_robot->getCurrentPlayerOrRobotID();
        $this->gamestate->nextState();
    }
}

?>
