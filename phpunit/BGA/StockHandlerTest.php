<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/StockHandler.php');
include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');

class StockHandlerTest extends TestCase{
    protected StockHandler $sut;

    protected function setUp(): void {
        $this->mockNotify = $this->createMock(PlayerRobotNotifications::class);

        $this->sut = new StockHandler();
        $this->sut->setNotificationsHandler($this->mockNotify);
    }

    public function testnewStockContent_Private_NotifyPlayer() {
        // Arrange
        $stock_id = 'Stock';
        $items = [];
        $message = 'Test';
        $player_id = 'Player';
        $arguments = [StockHandler::ARGUMENT_KEY_STOCK => $stock_id, StockHandler::ARGUMENT_KEY_STOCK_ITEMS => $items];

        $this->mockNotify->expects($this->exactly(1))->method('notifyPlayer')->with($player_id, StockHandler::EVENT_NEW_STOCK_CONTENT, $message, $arguments);
        // Act
        $this->sut->setNewStockContent($player_id, $stock_id, $items, $message);
        // Assert
    }

    public function testMoveCard_PrivateToPrivate_NotifyPlayer() {
        // Arrange
        $from = 'Stock1';
        $to = 'Stock2';
        $item = [];
        $message = 'Test';
        $player_id = 55;
        $arguments = [StockHandler::ARGUMENT_KEY_STOCK_FROM => $from, StockHandler::ARGUMENT_KEY_STOCK_TO => $to, StockHandler::ARGUMENT_KEY_STOCK_ITEM => $item];

        $this->mockNotify->expects($this->exactly(1))->method('notifyPlayer')->with($player_id, StockHandler::EVENT_STOCK_TO_STOCK, $message, $arguments);
        // Act
        $this->sut->moveCardPrivate($player_id, $from, $to, $item, $message);
        // Assert
    }

    public function testMoveCard_PrivateToPublic_NotifyPlayer() {
        // Arrange
        $from = 'Stock1';
        $to = 'Stock2';
        $item = [];
        $message_private = 'Test1';
        $message_public = 'Test2';
        $player_id = 55;
        $arguments = [StockHandler::ARGUMENT_KEY_STOCK_FROM => $from, StockHandler::ARGUMENT_KEY_STOCK_TO => $to, StockHandler::ARGUMENT_KEY_STOCK_ITEM => $item];

        $this->mockNotify->expects($this->exactly(1))->method('notifyPlayer')->with($player_id, StockHandler::EVENT_STOCK_TO_STOCK, $message_private, $arguments);
        $this->mockNotify->expects($this->exactly(1))->method('notifyAllPlayers')->with(StockHandler::EVENT_PLAYER_TO_STOCK, $message_public, $arguments, $player_id);
        // Act
        $this->sut->moveCardPrivatePublic($player_id, $from, $to, $item, $message_private, $message_public);
        // Assert
    }
}
?>
