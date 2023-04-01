<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/CardsHandler.php');
include_once(__DIR__.'/../export/modules/NotifyHandler.php');

include_once(__DIR__.'/../export/modules/BGA/CardsInterface.php');
include_once(__DIR__.'/../export/modules/BGA/NotifyInterface.php');

class CardsHandlerTest extends TestCase{
    public function setup() : void {
        $this->mockCards = $this->createMock(\NieuwenhovenGames\BGA\CardsInterface::class);
        $this->mockNotify = $this->createMock(NotifyHandler::class);
        $this->sut = CardsHandler::create($this->mockCards)->setNotifyHandler($this->mockNotify);
    }

    public function testswapHands_NoPlayers_NoAction() {
        // Arrange
        $this->mockNotify->expects($this->exactly(0))->method('notifyPlayerHand');
        $this->mockCards->expects($this->exactly(0))->method('moveAllCardsInLocation');
        // Act
        $this->sut->swapHands([]);
        // Assert
    }

    public function testswapHands_2Players_Swap() {
        // Arrange
        $this->mockCards->expects($this->exactly(3))->method('moveAllCardsInLocation');
        // Act
        $this->sut->swapHands([11, 12]);
        // Assert
    }

    public function testswapHands_2Players2Robots_4Notify() {
        // Arrange
        $this->mockNotify->expects($this->exactly(4))->method('notifyPlayerHand');
        // Act
        $this->sut->swapHands([2, 3, 11, 12]);
        // Assert
    }

    public function testExtraCard_Move_Notify() {
        // Arrange
        $this->mockCards->expects($this->exactly(1))->method('moveCard');
        $this->mockNotify->expects($this->exactly(1))->method('notifyCardMoved');
        // Act
        $this->sut->selectExtraCard(5);
        // Assert
    }
}
?>
