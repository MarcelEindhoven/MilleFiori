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

    public function testEmit_NoSubscribers_NothingHappens() {
        // Arrange
        // Act
        // Assert
    }
}
?>
