<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class PlayerRobotSetup {
    const DATABASE_PLAYER = 'player';
    const DATABASE_ROBOT = 'robot';
    const ID = 'player_id';
    const NUMBER = 'player_no';
    const COLOR = 'player_color';
    const CANAL = 'player_canal';
    const NAME = 'player_name';
    const AVATAR = 'player_avatar';
    const OCEAN = 'ocean_position';

    const FIELDS_PLAYER = [PlayerRobotSetup::ID, PlayerRobotSetup::COLOR, PlayerRobotSetup::CANAL, PlayerRobotSetup::NAME, PlayerRobotSetup::AVATAR, PlayerRobotSetup::OCEAN];
    const FIELDS_ROBOT = [PlayerRobotSetup::NUMBER, PlayerRobotSetup::ID, PlayerRobotSetup::COLOR, PlayerRobotSetup::NAME, PlayerRobotSetup::OCEAN];

    const CREATE_PLAYERS = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar, ocean_position) VALUES ";
    const CREATE_ROBOTS = "INSERT INTO robot (player_id, player_number, player_color, player_name, ocean_position) VALUES ";

    static public function create($storage) : PlayerRobotSetup {
        $object = new PlayerRobotSetup();
        return $object->setDatabase($storage);
    }

    public function setDatabase($storage) : PlayerRobotSetup {
        $this->storage = $storage;
        return $this;
    }

    public function setup(array $players, array $default_colors) : PlayerRobotSetup {
        $remaining_colours = $this->setupPlayers($players, $default_colors);

        $this->setupRobots(count($players) + 1, 4 - count($players), $remaining_colours);

        return $this;
    }

    private function setupRobots(int $player_number_offset, int $robot_count, array $colors) {
        if ($robot_count <= 0) {
            return;
        }

        $values = array();

        for ($robot_index = 1; $robot_index <= $robot_count; $robot_index++) {
            $color = array_shift($colors);
            $player_number = $player_number_offset + $robot_index - 1;
            $values[] = [$player_number, $robot_index, $color, "robot_$robot_index", 0];
        }

        $this->storage->createBucket(PlayerRobotSetup::DATABASE_ROBOT, PlayerRobotSetup::FIELDS_ROBOT, $values);
    }

    private function setupPlayers(array $players, array &$default_colors)
    {
        $values = array();
        
        foreach ($players as $player_id => $player)
        {
            $color = array_shift($default_colors);
            $values[] = [$player_id, $color, $player['player_canal'], addslashes( $player['player_name']), addslashes( $player['player_avatar']), 0];
        }
        
        $this->storage->createBucket(PlayerRobotSetup::DATABASE_PLAYER, PlayerRobotSetup::FIELDS_PLAYER, $values);

        return $default_colors;
    }

}

?>

