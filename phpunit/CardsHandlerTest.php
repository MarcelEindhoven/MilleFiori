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
include_once(__DIR__.'/../export/modules/ActionsAndStates/NotifyHandler.php');

include_once(__DIR__.'/../export/modules/BGA/Deck.php');
include_once(__DIR__.'/../export/modules/BGA/NotifyInterface.php');

class CardsHandlerTest extends TestCase{
    public function setup() : void {
        $this->mockCards = $this->createMock(\NieuwenhovenGames\BGA\Deck::class);
        $this->mockNotify = $this->createMock(NotifyHandler::class);
        $this->sut = CardsHandler::create($this->mockCards)->setNotifyHandler($this->mockNotify);
    }

    public function testExtraCard_Move_Notify() {
        // Arrange
        $this->mockCards->expects($this->exactly(1))->method('moveCard');
        $this->mockNotify->expects($this->exactly(1))->method('notifyCardMoved');
        // Act
        $this->sut->selectExtraCard(5);
        // Assert
    }

    public function testCardPlayed_Empty_Notify() {
        // Arrange
        $this->mockCards->expects($this->exactly(1))->method('moveAllCardsInLocation');
        $this->mockNotify->expects($this->exactly(1))->method('notifyEmptyPlayedHand');
        // Act
        $this->sut->emptyPlayedHand();
        // Assert
    }

    public function testSelectedCard_Number_countCardsByLocationArgs() {
        // Arrange
        $this->mockCards->expects($this->exactly(1))->method('countCardsByLocationArgs')->will($this->returnValue([1 => 2, 2 =>0, 3 => 1]));
        $expected_total = 2+1;
        // Act
        $total = $this->sut->getNumberSelectedCards();
        // Assert
        $this->assertEquals($expected_total, $total);
    }

    public function testSelectedCard_Play_MoveNotify() {
        // Arrange
        $player_id = 3;
        $cards = [[]];
        $this->mockCards->expects($this->exactly(1))->method('getCardsInLocation')->will($this->returnValue($cards));

        $this->mockCards->expects($this->exactly(1))->method('moveAllCardsInLocation');
        $this->mockNotify->expects($this->exactly(1))->method('notifyCardMovedFromPrivateToPublic');
        // Act
        $this->sut->playSelectedCard($player_id);
        // Assert
    }

    public function testHandCard_Number_countCardsByLocationArgs() {
        // Arrange
        $this->mockCards->expects($this->exactly(1))->method('countCardsByLocationArgs')->will($this->returnValue([1 => 2, 2 =>0, 3 => 1]));
        $expected_total = 2+1;
        // Act
        $total = $this->sut->getNumberPlayerCards();
        // Assert
        $this->assertEquals($expected_total, $total);
    }
}
?>
