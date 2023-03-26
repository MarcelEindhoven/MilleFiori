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

include_once(__DIR__.'/../export/modules/BGA/CardsInterface.php');
include_once(__DIR__.'/../export/modules/BGA/NotifyInterface.php');

class CardsHandlerTest extends TestCase{
    public function setup() : void {
        $this->mockCards = $this->createMock(\NieuwenhovenGames\BGA\CardsInterface::class);
        $this->mockNotify = $this->createMock(\NieuwenhovenGames\BGA\NotifyInterface::class);
        $this->sut = CardsHandler::create($this->mockCards)->setNotifyInterface($this->mockNotify);
    }

    public function testswapHands_NoPlayers_NoAction() {
        // Arrange
        $this->mockNotify->expects($this->exactly(0))->method('notifyPlayer');
        // Act
        $this->sut->swapHands([]);
        // Assert
    }
}
?>
