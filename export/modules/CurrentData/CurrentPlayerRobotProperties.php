<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/Storage.php');

include_once(__DIR__.'/../PlayerRobotProperties.php');

class CurrentPlayerRobotProperties extends PlayerRobotProperties {

    public static function create($storage) : CurrentPlayerRobotProperties {
        $object = new CurrentPlayerRobotProperties();
        return $object->setDatabase($storage);
    }

    public function setDatabase($storage) : CurrentPlayerRobotProperties {
        $this->storage = $storage;
        return $this;
    }

    public function getPlayerData(): array {
        $properties = $this->storage->getBucket(PlayerRobotProperties::PLAYER_BUCKET_NAME, PlayerRobotProperties::BUCKET_KEYS, PlayerRobotProperties::PLAYER_KEY_PREFIX);
        foreach ($properties as & $property) {
            $property['is_player'] = true;
        }
        return $properties;
    }

    public function getRobotData(): array {
        $properties = $this->storage->getBucket(PlayerRobotProperties::ROBOT_BUCKET_NAME, PlayerRobotProperties::BUCKET_KEYS, PlayerRobotProperties::PLAYER_KEY_PREFIX);
        foreach ($properties as & $property) {
            $property['is_player'] = false;
        }
        return $properties;
    }

    public function getPlayerDataIncludingRobots(): array {
        return $this->getPlayerData() + $this->getRobotData();
    }
}

?>
