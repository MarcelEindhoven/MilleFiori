<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/PlayerRobotProperties.php');

class Robot {
    static public function create(array $robotsProperties): array {
        $robots = [];
        foreach($robotsProperties as $robotProperties) {
            $robot = new Robot();
            $robots[] = $robot->setProperties($robotProperties);
        }
        return $robots;
    }

    public function getPlayerID(): int {
        return $this->robotProperties[PlayerRobotProperties::KEY_ID];
    }

    public function setProperties($robotProperties): Robot {
        $this->robotProperties = $robotProperties;
        return $this;
    }

    public function selectCard(array $IDs) {
        if (! $IDs) {
            return;
        }
        // For now, any will do
        return array_shift($IDs);
    }

    public function selectField(array $IDs) {
        if (! $IDs) {
            return;
        }
        // For now, any will do
        return array_shift($IDs);
    }
}

?>
