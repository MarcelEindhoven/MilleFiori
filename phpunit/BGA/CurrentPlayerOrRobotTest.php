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

class CurrentPlayerOrRobotTest extends TestCase{
    protected CurrentPlayerOrRobot $sut;

    protected function setUp(): void {
        $this->sut = CurrentPlayerOrRobot::create(0);
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

    public function testIsRobotID_Small_IsRobot() {
        // Arrange
        $player_id = 5;
        // Act
        $is_robot = $this->sut->isIDRobot($player_id);
        // Assert
        $this->assertTrue($is_robot);
    }

    public function testIsRobotID_Large_IsPlayer() {
        // Arrange
        $player_id = 55;
        // Act
        $is_robot = $this->sut->isIDRobot($player_id);
        // Assert
        $this->assertFalse($is_robot);
    }
}
?>
