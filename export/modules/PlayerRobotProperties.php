<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/BGA/Database.php');

class PlayerRobotProperties {
    const BUCKET_KEYS = [PlayerRobotProperties::KEY_ID, PlayerRobotProperties::KEY_SCORE, PlayerRobotProperties::KEY_NUMBER, PlayerRobotProperties::KEY_NAME, PlayerRobotProperties::KEY_COLOR, PlayerRobotProperties::KEY_POSITION];
    const PLAYER_BUCKET_NAME = 'player';
    const PLAYER_KEY_PREFIX = 'player_';
    const ROBOT_BUCKET_NAME = 'robot';

    const UPDATE_OCEAN_POSITION = "UPDATE player SET player_ocean_position=";
    const DATABASE_PLAYER = 'player';
    const DATABASE_ROBOT = 'robot';

    const QUERY_PLAYER = "SELECT player_id id, player_no number, player_score score, player_color color, player_ocean_position ocean_position FROM player";
    const QUERY_WHERE = " WHERE player_id=";
    const QUERY_ROBOT = "SELECT player_id id, player_no number, player_score score, player_color color, player_ocean_position ocean_position FROM robot";

    const KEY_POSITION = 'ocean_position';
    const KEY_SCORE = 'score';
    const KEY_ID = 'id';
    const KEY_NUMBER = 'no';
    const KEY_COLOR = 'color';
    const KEY_NAME = 'name';

    static public function create($sqlDatabase) : PlayerRobotProperties {
        $properties = new PlayerRobotProperties();
        return $properties->setDatabase($sqlDatabase);
    }

    public function setDatabase($sqlDatabase) : PlayerRobotProperties {
        $this->sqlDatabase = $sqlDatabase;
        return $this;
    }

    public function setNotifyInterface($notifyInterface) : PlayerRobotProperties {
        $this->notifyInterface = $notifyInterface;
        return $this;
    }

    public function setupNewGame(array $players, array $default_colors) : PlayerRobotProperties {
        $remaining_colours = $this->setupPlayers($players, $default_colors);

        $this->setupRobots(count($players) + 1, 4 - count($players), $remaining_colours);

        return $this;
    }

    public function getPropertiesPlayersPlusRobots() {
        $properties_list = $this->getPropertiesPlayers();

        if (count($properties_list) < 4) {
            $properties_list += $this->mapIDToDataContainingID($this->sqlDatabase->getObjectList(PlayerRobotProperties::QUERY_ROBOT));
        }

        return $properties_list;
    }

    public function getPropertiesPlayers() {
        return $this->mapIDToDataContainingID($this->sqlDatabase->getObjectList(PlayerRobotProperties::QUERY_PLAYER));
    }

    public function getProperty(int $player_id, string $property_key) {
        $database = $this->getDatabase($player_id);

        $property_key_with_prefix = 'player_' . $property_key;

        return $this->sqlDatabase->getObject("SELECT {$property_key_with_prefix} FROM {$database} WHERE player_id={$player_id}")[$property_key_with_prefix];
    }

    public function setProperty(int $player_id, string $property_key, $property_value) : PlayerRobotProperties {
        $database = $this->getDatabase($player_id);
        $property_key_with_prefix = 'player_' . $property_key;

        $this->sqlDatabase->query("UPDATE {$database} SET {$property_key_with_prefix}={$property_value} WHERE player_id={$player_id}");

        return $this;
    }

    public function setOceanPosition(int $player_id, int $player_ocean_position) : PlayerRobotProperties {
        $this->setProperty($player_id, PlayerRobotProperties::KEY_POSITION, $player_ocean_position);

        $this->notifyInterface->notifyAllPlayers('shipMoved', '', ['playersIncludingRobots' => $this->getPropertiesPlayersPlusRobots()]);

        return $this;
    }

    public function addScore(int $player_id, int $delta_score) : PlayerRobotProperties {
        $newScore = $delta_score + $this->getProperty($player_id, PlayerRobotProperties::KEY_SCORE);
        $this->setProperty($player_id, PlayerRobotProperties::KEY_SCORE, $newScore);

        if (! $this->isPlayerARobot($player_id)) {
            $this->notifyInterface->notifyAllPlayers('newScore', '', ['newScore' => $newScore, 'player_id' => $player_id]);
        }

        return $this;
    }

    private function getDatabase(int $player_id) {
        if ($this->isPlayerARobot($player_id)) {
            return PlayerRobotProperties::DATABASE_ROBOT;
        } else {
            return PlayerRobotProperties::DATABASE_PLAYER;
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
        return $this->mapIDToDataContainingID($this->sqlDatabase->getObjectList(PlayerRobotProperties::QUERY_ROBOT));
    }
}

?>

