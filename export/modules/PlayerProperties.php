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
    static public function create(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : PlayerProperties {
        $properties = new PlayerProperties();
        return $properties->setDatabase($sqlDatabase);
    }

    public function setDatabase(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : PlayerProperties {
        $this->sqlDatabase = $sqlDatabase;
        return $this;
    }

    public function setupNewGame($players, $default_colors) : PlayerProperties {
        $remaining_colours = $this->setupPlayers($players, $default_colors);

        $this->setupRobots(4 - count($players), $remaining_colours);

        return $this;
    }
    private function setupRobots($robot_count, $colors) {
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

    private function setupPlayers($players, &$default_colors)
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

