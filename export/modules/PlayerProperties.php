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
        $sql = PlayerProperties::CREATE_PLAYERS;
        $values = array();

        foreach ($players as $player_id => $player)
        {
            $color = array_shift($default_colors);
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."','0"."')";
        }

        $sql = PlayerProperties::CREATE_PLAYERS . implode(',', $values);
        $this->sqlDatabase->query($sql);

        if (count($players) < 4) {
            $values = array();
            for ($i=count($players); $i<4; $i++) {
                $color = array_shift($default_colors);
                $values[] = "('$i','" . $color . "','robot_$i','0')";
            }
    
            $sql = PlayerProperties::CREATE_ROBOTS . implode(',', $values);
            $this->sqlDatabase->query($sql);
        }

        return $this;
    }

}

?>
