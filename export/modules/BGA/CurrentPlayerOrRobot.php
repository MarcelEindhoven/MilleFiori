<?php
namespace NieuwenhovenGames\BGA;
/**
 * Choose the next player or robot
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/UpdatePlayerRobotProperties.php');

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

    public function next() : CurrentPlayerOrRobot {
        $new_number = $this->getNextPlayerOrRobotNumber();

        $this->setCurrentPlayerOrRobotNumber($new_number);

        return $this;
    }

    protected function isNumberOutOfBounds($number) {
        return $number >= UpdatePlayerRobotProperties::FIRST_PLAYER_NUMBER + count($this->properties);
    }

    protected function setCurrentPlayerOrRobotNumber($new_number) {
        foreach($this->properties as $player_id => $player_properties) {
            if ($new_number == $player_properties[UpdatePlayerRobotProperties::KEY_NUMBER]) {
                $this->player_id = $player_id;
            }
        }
    }

    protected function getNextPlayerOrRobotNumber(): int {
        $new_number = $this->properties[$this->player_id][UpdatePlayerRobotProperties::KEY_NUMBER] + 1;
        if ($this->isNumberOutOfBounds($new_number)) {
            $new_number = UpdatePlayerRobotProperties::FIRST_PLAYER_NUMBER;
        }
        return $new_number;
    }

    public function getCurrentPlayerOrRobotID(): int {return $this->player_id;}
    public function setCurrentPlayerOrRobotID($player_id) {$this->player_id = $player_id;}
    public function isIDRobot($player_id) {return $player_id <10;}
}
?>
