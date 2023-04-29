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

    static public function create() : CurrentPlayerOrRobot {
        $object = new CurrentPlayerOrRobot();
        return $object;
    }

    public function setPlayerAndRobotProperties($properties) : CurrentPlayerOrRobot {
        $this->properties = $properties;
        return $this;
    }

    public function getCurrentPlayerOrRobotID(): int {return $this->player_id;}
    public function setCurrentPlayerOrRobotID($player_id) {$this->player_id = $player_id;}
}
?>
