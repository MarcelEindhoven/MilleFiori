<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/Robot.php');

class RobotHandler {
    static public function create(): RobotHandler {
        $object = new RobotHandler();
        $object->player_id = 0;
        $object->robots = [];
        return $object;
    }

    public function createRobot($player_id, $data): Robot {
        $object = Robot::create($player_id, $data);
        $this->robots[$player_id] = $object;

        return $object;
    }

    public function setCurrentPlayerID($player_id) : RobotHandler {
        $this->player_id = $player_id;
        return $this;
    }

    public function getCurrentPlayerID(): int {
        return $this->player_id;
    }

    public function getCurrentRobot(): Robot {
        return $this->robots[$this->player_id];
    }

    public function getRobot($player_id): Robot {
        return $this->robots[$player_id];
    }

    public function getRobots(): array {
        return $this->robots;
    }
}

?>
