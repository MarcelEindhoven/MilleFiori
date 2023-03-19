<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/PlayerProperties.php');

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
        return $this->robotProperties[PlayerProperties::KEY_ID];
    }

    public function setProperties($robotProperties): Robot {
        $this->robotProperties = $robotProperties;
        return $this;
    }

    public function selectCard(array $cardIDs) {
        if (! $cardIDs) {
            return;
        }
        // For now, any card will do
        return array_shift($cardIDs);
    }
}

?>
