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
        $this->sut->setNewStockContent($player_id, $stock_id, $message, $items);
        // Assert
    }
}
?>
