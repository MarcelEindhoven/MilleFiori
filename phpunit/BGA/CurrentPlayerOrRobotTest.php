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
    protected \NieuwenhovenGames\BGA\CurrentPlayerOrRobot $sut;

    protected function setUp(): void {
        $this->sut = \NieuwenhovenGames\BGA\CurrentPlayerOrRobot::create();
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
}
?>
