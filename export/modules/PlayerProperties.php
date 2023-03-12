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
    const CREATE_ROBOTS = "INSERT INTO robot (player_id, player_color, player_name, ocean_position) VALUES ";

    const QUERY_PLAYER = "SELECT player_id id, player_score score, player_color color, ocean_position ocean_position FROM player";
    const QUERY_WHERE = " WHERE player_id=";
    const QUERY_ROBOT = "SELECT player_id id, player_score score, player_color color, ocean_position ocean_position FROM robot";

    const KEY_POSITION = 'ocean_position';
    const KEY_ID = 'id';

    static public function create(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : PlayerProperties {
        $properties = new PlayerProperties();
        return $properties->setDatabase($sqlDatabase);
    }

    public function setDatabase(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : PlayerProperties {
        $this->sqlDatabase = $sqlDatabase;
        return $this;
    }

    public function setupNewGame(array $players, array $default_colors) : PlayerProperties {
        $remaining_colours = $this->setupPlayers($players, $default_colors);

        $this->setupRobots(4 - count($players), $remaining_colours);

        return $this;
    }

    public function getPropertiesPlayersPlusRobots() {
        $properties_list = array_values($this->sqlDatabase->getObjectList(PlayerProperties::QUERY_PLAYER));
        if (count($properties_list) < 4) {
            $properties_list += $this->sqlDatabase->getObjectList(PlayerProperties::QUERY_ROBOT);
        }
        return $properties_list;
    }


    private function setupRobots(int $robot_count, array $colors) {
        if ($robot_count <= 0) {
            return;
        }

        $values = array();

        for ($i= 0; $i< $robot_count; $i++) {
            $color = array_shift($colors);
            $values[] = "('$i','" . $color . "','robot_$i','0')";
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

