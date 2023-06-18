<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/NotifyHandler.php');

include_once(__DIR__.'/../../export/modules/BGA/CardsInterface.php');
include_once(__DIR__.'/../../export/modules/BGA/NotifyInterface.php');

class UpdateCardsTest extends TestCase{
    public function setup() : void {
        $this->mockCards = $this->createMock(\NieuwenhovenGames\BGA\CardsInterface::class);
        $this->mockNotify = $this->createMock(NotifyHandler::class);
        $this->sut = UpdateCards::create($this->mockCards)->setNotifyHandler($this->mockNotify);
    }

    public function testswapHands_NoPlayers_NoAction() {
        // Arrange
        $this->mockNotify->expects($this->exactly(0))->method('notifyPlayerHand');
        $this->mockCards->expects($this->exactly(0))->method('moveAllCardsInLocation');
        $this->sut->setPlayerIDs([]);
        // Act
        $this->sut->swapHands();
        // Assert
    }

    public function testswapHands_2Players_Swap() {
        // Arrange
        $this->mockCards->expects($this->exactly(3))->method('moveAllCardsInLocation');
        $this->sut->setPlayerIDs([11, 12]);
        // Act
        $this->sut->swapHands();
        // Assert
    }

    public function testswapHands_2Players2Robots_4Notify() {
        // Arrange
        $this->mockNotify->expects($this->exactly(4))->method('notifyPlayerHand');
        $this->sut->setPlayerIDs([2, 3, 11, 12]);
        // Act
        $this->sut->swapHands();
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

    public function testSameCardCount_HandUnequal_False() {
        // Arrange
        $this->mockCards->expects($this->exactly(1))->method('countCardsByLocationArgs')->will($this->returnValue([1 => 2, 2 =>0, 3 => 1]));
        // Act
        $have_same_card_count = $this->sut->haveAllPlayersSameHandCount();
        // Assert
        $this->assertFalse($have_same_card_count);
    }

    public function testSelectedHands_NotEmpty_True() {
        // Arrange
        $this->mockCards->expects($this->exactly(1))->method('countCardsByLocationArgs')->will($this->onConsecutiveCalls([[1 => 2, 3 => 1]]));
        // Act
        $are_any_cards_selected = $this->sut->areAnyCardsSelected();
        // Assert
        $this->assertTrue($are_any_cards_selected);
    }

    public function testSelectedHands_AllEmpty_False() {
        // Arrange
        $this->mockCards->expects($this->exactly(1))->method('countCardsByLocationArgs')->will($this->returnValue([]));
        // Act
        $are_any_cards_selected = $this->sut->areAnyCardsSelected();
        // Assert
        $this->assertFalse($are_any_cards_selected);
    }
}
?>
