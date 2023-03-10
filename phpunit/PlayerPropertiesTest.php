<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/PlayerProperties.php');

include_once(__DIR__.'/../export/modules/BGA/DatabaseInterface.php');

class PlayerPropertiesTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];
    public function setup() : void {
        $this->mock = $this->createMock(\NieuwenhovenGames\BGA\DatabaseInterface::class);
        $this->sut = PlayerProperties::create($this->mock);
    }

    private function arrangeCreate($number_players, $number_colors) {
        $this->players = [];
        for ($i=0; $i<$number_players; $i++) {
            $this->players[$i] = ['player_canal' => 0, 'player_name' => 'player_' . $i, 'player_avatar' => ''];
        }

        $query_create_players = $this->getPlayersCreateQuery($number_players);
        $query_create_robots = $this->getRobotsCreateQuery($number_players);

        if ($query_create_robots) {
            $this->mock->expects($this->exactly(2))->method('query')->withConsecutive([$this->equalTo($query_create_players)], [$this->equalTo($query_create_robots)]);
        } else {
            $this->mock->expects($this->exactly(1))->method('query')->with($this->equalTo($query_create_players));
        }
    }

    private function getRobotsCreateQuery($number_players) {
        if ($number_players >= 4) {
            return null;
        }
        $query_robot = PlayerProperties::CREATE_ROBOTS;
        for ($index = 0; $index < 4 - $number_players; $index++) {
            $query_robot .= $this->optionalSeparator($index);
            $query_robot .= "('$index','" . PlayerPropertiesTest::COLORS[$number_players + $index] . "','robot_$index','0')";
        }
        return $query_robot;
    }

    private function getPlayersCreateQuery($number_players) {
        $query = PlayerProperties::CREATE_PLAYERS;
        for ($i=0; $i<$number_players; $i++) {
            $query .= $this->optionalSeparator($i);
            $query .= "('$i','" . PlayerPropertiesTest::COLORS[$i] . "','0','player_$i','','0')";
        }
        return $query;
    }

    private function optionalSeparator(int $index) : string {
        if ($index > 0) {
            return ",";
        }
        return "";
    }

    public function defaultAct() {
        $this->sut->setupNewGame($this->players, PlayerPropertiesTest::COLORS);
    }

    public function testNewGame_2Players_Query() {
        // Arrange
        $this->arrangeCreate(2, 0);
        // Act
        $this->defaultAct();
        // Assert
    }

    public function testNewGame_4Players_Query() {
        // Arrange
        $this->arrangeCreate(4, 0);
        // Act
        $this->defaultAct();
        // Assert
    }

    public function testProperties_2Players_SelectPlayersRobots() {
        // Arrange
        $player_id = 2;
        $player_position = 5;
        $player_list = [0 => [PlayerProperties::KEY_ID => $player_id, PlayerProperties::KEY_POSITION => $player_position], 
        1 => [PlayerProperties::KEY_ID => $player_id + 1, PlayerProperties::KEY_POSITION => 0]];
        $robot_list = [0 => [PlayerProperties::KEY_ID => $player_id + 2, PlayerProperties::KEY_POSITION => $player_position], 
        1 => [PlayerProperties::KEY_ID => $player_id + 3, PlayerProperties::KEY_POSITION => 0]];
        $expected_list = [$player_id => $player_list[0], $player_id + 1 => $player_list[1], $player_id + 2 => $robot_list[0], $player_id + 3 => $robot_list[1], ];
        $this->mock->expects($this->exactly(2))->method('getObjectList')
            ->withConsecutive([$this->equalTo(PlayerProperties::QUERY_PLAYER)], [$this->equalTo(PlayerProperties::QUERY_ROBOT)])
            ->willReturnOnConsecutiveCalls($player_list, $robot_list);
        // Act
        $list = $this->sut->getPropertiesPlayersPlusRobots();
        // Assert
        $this->assertEquals($expected_list, $list);
    }

    public function testProperties_4Players_SelectOnlyPlayers() {
        // Arrange
        $player_id = 2;
        $player_position = 5;
        $player_list = [0 => [PlayerProperties::KEY_ID => $player_id, PlayerProperties::KEY_POSITION => $player_position]
            , 1 => [PlayerProperties::KEY_ID => $player_id + 1, PlayerProperties::KEY_POSITION => 0]
            , 2 => [PlayerProperties::KEY_ID => $player_id + 2, PlayerProperties::KEY_POSITION => 0]
            , 3 => [PlayerProperties::KEY_ID => $player_id + 3, PlayerProperties::KEY_POSITION => 0]
        ];
        $expected_list = [$player_id => $player_list[0], $player_id + 1 => $player_list[1], $player_id + 2 => $player_list[2], $player_id + 3 => $player_list[3], ];
        $this->mock->expects($this->exactly(1))->method('getObjectList')->with($this->equalTo(PlayerProperties::QUERY_PLAYER))->will($this->returnValue(
            $player_list));
        // Act
        $list = $this->sut->getPropertiesPlayersPlusRobots();
        // Assert
        $this->assertEquals($expected_list, $list);
    }

}
?>
