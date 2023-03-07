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
    static public function create(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : PlayerProperties {
        $properties = new PlayerProperties();
        return $properties->setDatabase($sqlDatabase);
    }

    public function setDatabase(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : PlayerProperties {
        $this->sqlDatabase = $sqlDatabase;
        return $this;
    }

    public function setupNewGame($players, $default_colors) : PlayerProperties {
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar, ocean_position) VALUES ";
        $values = array();

        foreach ($players as $player_id => $player)
        {
            $color = array_shift($default_colors);
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."','0"."')";
        }

        $sql .= implode($values, ',');
        $this->sqlDatabase->query($sql);

        return $this;
    }

}

?>
