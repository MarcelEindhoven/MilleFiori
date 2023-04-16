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
include_once(__DIR__.'/../export/modules/BGA/NotifyInterface.php');

class PlayerPropertiesTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];
    public function setup() : void {
        $this->mock = $this->createMock(\NieuwenhovenGames\BGA\DatabaseInterface::class);
        $this->mockNotify = $this->createMock(\NieuwenhovenGames\BGA\NotifyInterface::class);
        $this->sut = PlayerProperties::create($this->mock)->setNotifyInterface($this->mockNotify);
    }

    private function optionalSeparator(int $index) : string {
        if ($index > 0) {
            return ",";
        }
        return "";
    }

    public function testRobotProperties_4Players_NoRobots() {
        // Arrange
        $this->mock->expects($this->exactly(1))->method('getObjectList')
            ->with($this->equalTo(PlayerProperties::QUERY_ROBOT))
            ->will($this->returnValue([]));
        // Act
        $robotProperties = $this->sut->getRobotProperties();
        // Assert
        $this->assertEquals([], $robotProperties);
    }

    public function testProperties_2Players_SelectPlayersRobots() {
        // Arrange
        $expected_list = $this->expectgetProperties2PlayersPlus2Robots();

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

    public function testProperties_RobotGet_SQLSelect() {
        // Arrange
        $player_id = $this->createRobotID();
        $database = PlayerProperties::DATABASE_ROBOT;
        $property_key = PlayerProperties::KEY_POSITION;
        $expected_property_value = 4;
        $this->mock->expects($this->exactly(1))->method('getObject')
            ->with($this->equalTo("SELECT {$property_key} FROM {$database} WHERE player_id={$player_id}"))
            ->will($this->returnValue([$property_key => $expected_property_value]));

        // Act
        $property_value = $this->sut->getProperty($player_id, $property_key);
        // Assert
    }

    public function testProperties_RobotSetOcean_SQLUpdate() {
        // Arrange
        $player_id = $this->createRobotID();
        $property_value = 5;

        $expected_list = $this->expectgetProperties2PlayersPlus2Robots();
        $this->mockNotify->expects($this->exactly(1))->method('notifyAllPlayers');

        $query = $this->createQueryUpdate(PlayerProperties::DATABASE_ROBOT, PlayerProperties::KEY_POSITION, $player_id, $property_value);
        $this->mock->expects($this->exactly(1))->method('query')->with($this->equalTo($query));
        // Act
        $this->sut->setOceanPosition($player_id, $property_value);
        // Assert
    }

    public function testProperties_PlayerSetOcean_SQLUpdatePlayer() {
        // Arrange
        $player_id = $this->createPlayerID();
        $property_value = 4;

        $query = $this->createQueryUpdate(PlayerProperties::DATABASE_PLAYER, PlayerProperties::KEY_POSITION, $player_id, $property_value);
        $this->mock->expects($this->exactly(1))->method('query')->with($this->equalTo($query));
        // Act
        $this->sut->setOceanPosition($player_id, $property_value);
        // Assert
    }

    private function expectgetProperties2PlayersPlus2Robots() {
        $player_id = $this->createRobotID();
        $player_position = 5;

        $player_list = [0 => [PlayerProperties::KEY_ID => $player_id, PlayerProperties::KEY_POSITION => $player_position], 
        1 => [PlayerProperties::KEY_ID => $player_id + 1, PlayerProperties::KEY_POSITION => 0]];

        $robot_list = [0 => [PlayerProperties::KEY_ID => $player_id + 2, PlayerProperties::KEY_POSITION => $player_position], 
        1 => [PlayerProperties::KEY_ID => $player_id + 3, PlayerProperties::KEY_POSITION => 0]];

        $this->mock->expects($this->exactly(2))->method('getObjectList')
            ->withConsecutive([$this->equalTo(PlayerProperties::QUERY_PLAYER)], [$this->equalTo(PlayerProperties::QUERY_ROBOT)])
            ->willReturnOnConsecutiveCalls($player_list, $robot_list);

        return [$player_id => $player_list[0], $player_id + 1 => $player_list[1], $player_id + 2 => $robot_list[0], $player_id + 3 => $robot_list[1], ];
    }

    private function createQueryUpdate($database, $property_key, $player_id, $property_value) {
        return "UPDATE {$database} SET {$property_key}={$property_value} WHERE player_id={$player_id}";
    }
    private function createRobotID() {
        return 3;
    }
    private function createPlayerID() {
        return 13;
    }

}
?>
