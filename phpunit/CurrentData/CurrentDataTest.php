<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentCards.php');
include_once(__DIR__.'/../../export/modules/CardsHandler.php');
include_once(__DIR__.'/../../export/modules/BGA/CardsInterface.php');

class CurrentDataTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];

    protected CurrentData $sut;

    protected function setUp(): void {
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\CardsInterface::class);
        $this->sut = CurrentData::create($this->mock_cards);
        $this->sut->setCards($this->mock_cards);
    }

    public function testGet_Integration_CardsInLocation() {
        // Arrange
        $player_id = 7;
        $this->mock_cards->expects($this->exactly(4))->method('getCardsInLocation')->will($this->returnValue(['x']));
        // Act
        $this->sut->getAllData($player_id);
        // Assert
    }
}
?>
