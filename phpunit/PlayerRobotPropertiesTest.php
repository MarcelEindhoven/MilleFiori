<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/PlayerRobotProperties.php');

include_once(__DIR__.'/../export/modules/BGA/Database.php');
include_once(__DIR__.'/../export/modules/BGA/Notifications.php');

class PlayerRobotPropertiesTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];
    public function setup() : void {
        $this->mock = $this->createMock(\NieuwenhovenGames\BGA\Database::class);
        $this->mockNotify = $this->createMock(\NieuwenhovenGames\BGA\Notifications::class);
        $this->sut = PlayerRobotProperties::create($this->mock)->setNotifications($this->mockNotify);
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
            ->with($this->equalTo(PlayerRobotProperties::QUERY_ROBOT))
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
        $player_list = [0 => [PlayerRobotProperties::KEY_ID => $player_id, PlayerRobotProperties::KEY_POSITION => $player_position]
            , 1 => [PlayerRobotProperties::KEY_ID => $player_id + 1, PlayerRobotProperties::KEY_POSITION => 0]
            , 2 => [PlayerRobotProperties::KEY_ID => $player_id + 2, PlayerRobotProperties::KEY_POSITION => 0]
            , 3 => [PlayerRobotProperties::KEY_ID => $player_id + 3, PlayerRobotProperties::KEY_POSITION => 0]
        ];
        $expected_list = [$player_id => $player_list[0], $player_id + 1 => $player_list[1], $player_id + 2 => $player_list[2], $player_id + 3 => $player_list[3], ];
        $this->mock->expects($this->exactly(1))->method('getObjectList')->with($this->equalTo(PlayerRobotProperties::QUERY_PLAYER))->will($this->returnValue(
            $player_list));
        // Act
        $list = $this->sut->getPropertiesPlayersPlusRobots();
        // Assert
        $this->assertEquals($expected_list, $list);
    }

    private function expectgetProperties2PlayersPlus2Robots() {
        $player_id = $this->createRobotID();
        $player_position = 5;

        $player_list = [0 => [PlayerRobotProperties::KEY_ID => $player_id, PlayerRobotProperties::KEY_POSITION => $player_position], 
        1 => [PlayerRobotProperties::KEY_ID => $player_id + 1, PlayerRobotProperties::KEY_POSITION => 0]];

        $robot_list = [0 => [PlayerRobotProperties::KEY_ID => $player_id + 2, PlayerRobotProperties::KEY_POSITION => $player_position], 
        1 => [PlayerRobotProperties::KEY_ID => $player_id + 3, PlayerRobotProperties::KEY_POSITION => 0]];

        $this->mock->expects($this->exactly(2))->method('getObjectList')
            ->withConsecutive([$this->equalTo(PlayerRobotProperties::QUERY_PLAYER)], [$this->equalTo(PlayerRobotProperties::QUERY_ROBOT)])
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
