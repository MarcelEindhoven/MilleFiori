<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameSetup/CardsSetup.php');
include_once(__DIR__.'/../../export/modules/BGA/CardsInterface.php');

class CardsSetupTest extends TestCase{
    const COLORS = ['green', 'red', 'blue', 'yellow'];

    protected CardsSetup $sut;

    protected function setUp(): void {
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\CardsInterface::class);
        $this->sut = CardsSetup::create($this->mock_cards);
    }

    public function testInitialisation_Sideboard_PickAndMove() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('pickCards');
        $this->mock_cards->expects($this->exactly(1))->method('moveAllCardsInLocation');
        // Act
        $total = $this->sut->initialiseSideboard(9);
        // Assert
    }
}
?>
