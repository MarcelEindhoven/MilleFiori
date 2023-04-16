<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/BGA/DatabaseInterface.php');

class PlayerProperties {
    const UPDATE_OCEAN_POSITION = "UPDATE player SET ocean_position=";
    const DATABASE_PLAYER = 'player';
    const DATABASE_ROBOT = 'robot';

    const QUERY_PLAYER = "SELECT player_id id, player_no number, player_score score, player_color color, ocean_position ocean_position FROM player";
    const QUERY_WHERE = " WHERE player_id=";
    const QUERY_ROBOT = "SELECT player_id id, player_no number, player_score score, player_color color, ocean_position ocean_position FROM robot";

    const KEY_POSITION = 'ocean_position';
    const KEY_PLAYER_SCORE = 'player_score';
    const KEY_ID = 'id';
    const KEY_NUMBER = 'number';

    static public function create($sqlDatabase) : PlayerProperties {
        $properties = new PlayerProperties();
        return $properties->setDatabase($sqlDatabase);
    }

    public function setDatabase($sqlDatabase) : PlayerProperties {
        $this->sqlDatabase = $sqlDatabase;
        return $this;
    }

    public function setNotifyInterface($notifyInterface) : PlayerProperties {
        $this->notifyInterface = $notifyInterface;
        return $this;
    }

    public function setupNewGame(array $players, array $default_colors) : PlayerProperties {
        $remaining_colours = $this->setupPlayers($players, $default_colors);

        $this->setupRobots(count($players) + 1, 4 - count($players), $remaining_colours);

        return $this;
    }

    public function getPropertiesPlayersPlusRobots() {
        $properties_list = $this->getPropertiesPlayers();

        if (count($properties_list) < 4) {
            $properties_list += $this->mapIDToDataContainingID($this->sqlDatabase->getObjectList(PlayerProperties::QUERY_ROBOT));
        }

        return $properties_list;
    }

    public function getPropertiesPlayers() {
        return $this->mapIDToDataContainingID($this->sqlDatabase->getObjectList(PlayerProperties::QUERY_PLAYER));
    }

    public function getProperty(int $player_id, string $property_key) {
        $database = $this->getDatabase($player_id);

        return $this->sqlDatabase->getObject("SELECT {$property_key} FROM {$database} WHERE player_id={$player_id}")[$property_key];
    }

    public function setProperty(int $player_id, string $property_key, $property_value) : PlayerProperties {
        $database = $this->getDatabase($player_id);

        $this->sqlDatabase->query("UPDATE {$database} SET {$property_key}={$property_value} WHERE player_id={$player_id}");

        return $this;
    }

    public function setOceanPosition(int $player_id, int $ocean_position) : PlayerProperties {
        $this->setProperty($player_id, PlayerProperties::KEY_POSITION, $ocean_position);

        $this->notifyInterface->notifyAllPlayers('shipMoved', '', ['players' => $this->getPropertiesPlayersPlusRobots()]);

        return $this;
    }

    public function addScore(int $player_id, int $delta_score) : PlayerProperties {
        $newScore = $delta_score + $this->getProperty($player_id, PlayerProperties::KEY_PLAYER_SCORE);
        $this->setProperty($player_id, PlayerProperties::KEY_PLAYER_SCORE, $newScore);

        if (! $this->isPlayerARobot($player_id)) {
            $this->notifyInterface->notifyAllPlayers('newScore', '', ['newScore' => $newScore, 'player_id' => $player_id]);
        }

        return $this;
    }

    private function getDatabase(int $player_id) {
        if ($this->isPlayerARobot($player_id)) {
            return PlayerProperties::DATABASE_ROBOT;
        } else {
            return PlayerProperties::DATABASE_PLAYER;
        }
    }

    private function mapIDToDataContainingID(array $list): array {
        $mapped_list = [];
        foreach ($list as $element) {
            $mapped_list[$element['id']] = $element;
        }
        return $mapped_list;
    }

    public function isPlayerARobot(int $player_id) : bool {
        return $player_id < 9;
    }

    public function getRobotProperties() : array {
        return $this->mapIDToDataContainingID($this->sqlDatabase->getObjectList(PlayerProperties::QUERY_ROBOT));
    }
}

?>

