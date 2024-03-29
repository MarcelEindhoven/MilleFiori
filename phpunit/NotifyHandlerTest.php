<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/ActionsAndStates/NotifyHandler.php');

include_once(__DIR__.'/../export/modules/BGA/FrameworkInterfaces/Notifications.php');

class NotifyHandlerTest extends TestCase{
    public function setup() : void {
        $this->mockNotify = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\Notifications::class);
        $this->sut = NotifyHandler::create($this->mockNotify);
    }

    public function testnotifyPlayerHand_Robot_NoAction() {
        // Arrange
        $this->mockNotify->expects($this->exactly(0))->method('notifyPlayer');
        // Act
        $this->sut->notifyPlayerHand($this->createRobotID(), [], '');
        // Assert
    }

    public function testnotifyPlayerHand_Player_notifyPlayer() {
        // Arrange
        $this->mockNotify->expects($this->exactly(1))->method('notifyPlayer');
        // Act
        $this->sut->notifyPlayerHand($this->createPlayerID(), [], 'Pass hand to other player');
        // Assert
    }

    public function testnotifyPlayedHand_Remove_notifyAllPlayers() {
        // Arrange
        $this->mockNotify->expects($this->exactly(1))->method('notifyAllPlayers');
        // Act
        $this->sut->notifyEmptyPlayedHand();
        // Assert
    }

    private function createRobotID() {
        return 3;
    }
    private function createPlayerID() {
        return 13;
    }
}
?>
