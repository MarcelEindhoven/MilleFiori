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
    const CREATE_PLAYERS = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar, ocean_position) VALUES ";
    const CREATE_ROBOTS = "INSERT INTO robot (player_id, player_number, player_color, player_name, ocean_position) VALUES ";

    const QUERY_PLAYER = "SELECT player_id id, player_no number, player_score score, player_color color, ocean_position ocean_position FROM player";
    const QUERY_WHERE = " WHERE player_id=";
    const QUERY_ROBOT = "SELECT player_id id, player_number number, player_score score, player_color color, ocean_position ocean_position FROM robot";

    const KEY_POSITION = 'ocean_position';
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

    public function setupNewGame(array $players, array $default_colors) : PlayerProperties {
        $remaining_colours = $this->setupPlayers($players, $default_colors);

        $this->setupRobots(count($players) + 1, 4 - count($players), $remaining_colours);

        return $this;
    }

    public function getPropertiesPlayersPlusRobots() {
        $properties_list = $this->mapIDToDataContainingID($this->sqlDatabase->getObjectList(PlayerProperties::QUERY_PLAYER));
        if (count($properties_list) < 4) {
            $properties_list += $this->mapIDToDataContainingID($this->sqlDatabase->getObjectList(PlayerProperties::QUERY_ROBOT));
        }
        return $properties_list;
    }

    private function mapIDToDataContainingID(array $list): array {
        $mapped_list = [];
        foreach ($list as $element) {
            $mapped_list[$element['id']] = $element;
        }
        return $mapped_list;
    }

    public function isPlayerARobot($player_id) : bool {
        return $player_id < 9;
    }

    public function getRobotProperties() : array {
        return $this->mapIDToDataContainingID($this->sqlDatabase->getObjectList(PlayerProperties::QUERY_ROBOT));
    }

    private function setupRobots(int $player_number_offset, int $robot_count, array $colors) {
        if ($robot_count <= 0) {
            return;
        }

        $values = array();

        for ($robot_index = 0; $robot_index < $robot_count; $robot_index++) {
            $color = array_shift($colors);
            $player_number = $player_number_offset + $robot_index;
            $values[] = "('$robot_index','$player_number','$color','robot_$robot_index','0')";
        }

        $sql = PlayerProperties::CREATE_ROBOTS . implode(',', $values);
        $this->sqlDatabase->query($sql);
    }

    private function setupPlayers(array $players, array &$default_colors)
    {
        $sql = PlayerProperties::CREATE_PLAYERS;
        $values = array();
        
        foreach ($players as $player_id => $player)
        {
            $color = array_shift($default_colors);
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."','0"."')";
        }
        
        $sql = PlayerProperties::CREATE_PLAYERS . implode(',', $values);
        $this->sqlDatabase->query($sql);

        return $default_colors;
    }

}

?>

