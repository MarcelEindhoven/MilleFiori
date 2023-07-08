<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class CurrentPlayerRobotPropertiesTest extends TestCase{
    const FIELDS = ['id', 'score', 'no', 'name', 'color', 'ocean_position'];
    const PLAYER_DATA = [55 => ['name' => 'TEST']];
    const ROBOT_DATA = [5 => ['name' => 'TESTR']];
    const PLAYER_EXPECTED_DATA = [55 => ['name' => 'TEST', 'is_robot' => false]];
    const ROBOT_EXPECTED_DATA = [5 => ['name' => 'TESTR', 'is_robot' => true]];
    const PLAYER_BUCKET_INPUT_DATA = ['player', CurrentPlayerRobotPropertiesTest::FIELDS, 'player_'];
    const ROBOT_BUCKET_INPUT_DATA = ['robot', CurrentPlayerRobotPropertiesTest::FIELDS, 'player_'];

    protected CurrentPlayerRobotProperties $sut;

    protected function setUp(): void {
        $this->mock_storage = $this->createMock(\NieuwenhovenGames\BGA\Storage::class);
        $this->sut = CurrentPlayerRobotProperties::create($this->mock_storage);
    }

    public function testProperties_GetPlayer_GetBucket() {
        // Arrange
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        $this->mock_storage->expects($this->exactly(1))
        ->method('getBucket')
        ->with('player', CurrentPlayerRobotPropertiesTest::FIELDS, 'player_')
        ->will($this->returnValue(CurrentPlayerRobotPropertiesTest::PLAYER_DATA));
        // Act
        $data = $this->sut->getPlayerData();
        // Assert
        $this->assertEquals(CurrentPlayerRobotPropertiesTest::PLAYER_EXPECTED_DATA, $data);
    }

    public function testProperties_GetPlayerRobot_GetBucket() {
        // Arrange

        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        $this->mock_storage->expects($this->exactly(2))
        ->method('getBucket')
        ->withConsecutive(CurrentPlayerRobotPropertiesTest::PLAYER_BUCKET_INPUT_DATA, CurrentPlayerRobotPropertiesTest::ROBOT_BUCKET_INPUT_DATA)
        ->willReturnOnConsecutiveCalls(CurrentPlayerRobotPropertiesTest::PLAYER_DATA, CurrentPlayerRobotPropertiesTest::ROBOT_DATA);
        // Act
        $data = $this->sut->getPlayerDataIncludingRobots();
        // Assert
        $this->assertEquals(CurrentPlayerRobotPropertiesTest::PLAYER_EXPECTED_DATA + CurrentPlayerRobotPropertiesTest::ROBOT_EXPECTED_DATA, $data);
    }
}
?>
