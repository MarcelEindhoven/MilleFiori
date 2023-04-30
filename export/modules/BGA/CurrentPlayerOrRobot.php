<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/DatabaseInterface.php');

class CurrentPlayerOrRobot {

    static public function create($current_player_or_robot_id) : CurrentPlayerOrRobot {
        $object = new CurrentPlayerOrRobot();
        $object->setCurrentPlayerOrRobotID($current_player_or_robot_id);
        return $object;
    }

    public function setPlayerAndRobotProperties($properties) : CurrentPlayerOrRobot {
        $this->properties = $properties;
        return $this;
    }

    public function getCurrentPlayerOrRobotID(): int {return $this->player_id;}
    public function setCurrentPlayerOrRobotID($player_id) {$this->player_id = $player_id;}
    public function isIDRobot($player_id) {return $player_id <10;}
}
?>