<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerOrRobot.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdatePlayerRobotProperties.php');

include_once(__DIR__.'/UpdatePlayerRobotPropertiesTest.php');

class CurrentPlayerOrRobotTest extends TestCase{
    const DEFAULT_ROBOT_NUMBER = CurrentPlayerOrRobot::FIRST_PLAYER_NUMBER;
    const DEFAULT_PLAYER_NUMBER = 2;
    const DEFAULT_DATA = [
        UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID => [
            UpdatePlayerRobotProperties::KEY_ID => UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID,
            UpdatePlayerRobotProperties::KEY_NUMBER => CurrentPlayerOrRobotTest::DEFAULT_ROBOT_NUMBER,
            UpdatePlayerRobotPropertiesTest::DEFAULT_KEY => UpdatePlayerRobotPropertiesTest::DEFAULT_VALUE,
            UpdatePlayerRobotProperties::KEY_NAME => UpdatePlayerRobotPropertiesTest::DEFAULT_NAME,
            UpdatePlayerRobotProperties::KEY_IS_PLAYER => False,
        ],
        UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID => [
            UpdatePlayerRobotProperties::KEY_ID => UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID,
            UpdatePlayerRobotProperties::KEY_NUMBER => CurrentPlayerOrRobotTest::DEFAULT_PLAYER_NUMBER,
            UpdatePlayerRobotPropertiesTest::DEFAULT_KEY => UpdatePlayerRobotPropertiesTest::DEFAULT_VALUE,
            UpdatePlayerRobotProperties::KEY_NAME => UpdatePlayerRobotPropertiesTest::DEFAULT_NAME,
            UpdatePlayerRobotProperties::KEY_IS_PLAYER => True,
        ]
    ];

    protected CurrentPlayerOrRobot $sut;

    protected function setUp(): void {
        $this->sut = CurrentPlayerOrRobot::create(0);
        $this->sut->setPlayerAndRobotProperties(CurrentPlayerOrRobotTest::DEFAULT_DATA);

        $this->mock_gamestate = $this->createMock(FrameworkInterfaces\GameState::class);
        $this->sut->setGameState($this->mock_gamestate);
    }

    public function testID_NoChange_GetEqualsSet() {
        // Arrange
        $player_id = 55;
        $this->sut->setCurrentPlayerOrRobotID($player_id);
        // Act
        $id = $this->sut->getCurrentPlayerOrRobotID();
        // Assert
        $this->assertEquals($player_id, $id);
    }

    // Next robot or player
    public function testNext_First_Second() {
        // Arrange
        $this->sut->setCurrentPlayerOrRobotID(UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID);
        $expected_player_id = UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID;
        // Act
        $this->sut->nextPlayerOrRobot();
        // Assert
        $this->assertEquals($expected_player_id, $this->sut->getCurrentPlayerOrRobotID());
    }

    public function testNext_Last_First() {
        // Arrange
        $this->sut->setCurrentPlayerOrRobotID(UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID);
        $expected_player_id = UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID;
        // Act
        $this->sut->nextPlayerOrRobot();
        // Assert
        $this->assertEquals($expected_player_id, $this->sut->getCurrentPlayerOrRobotID());
    }

    public function testNext_Robot_DoNotActivate() {
        // Arrange
        $this->sut->setCurrentPlayerOrRobotID(UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID);
        $this->mock_gamestate->expects($this->exactly(0))->method('changeActivePlayer');
        // Act
        $this->sut->nextPlayerOrRobot();
        // Assert
    }

    public function testNext_Player_Activate() {
        // Arrange
        $this->sut->setCurrentPlayerOrRobotID(UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID);
        $this->mock_gamestate->expects($this->exactly(1))->method('changeActivePlayer')->with(UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID);
        // Act
        $this->sut->nextPlayerOrRobot();
        // Assert
    }
}
?>
