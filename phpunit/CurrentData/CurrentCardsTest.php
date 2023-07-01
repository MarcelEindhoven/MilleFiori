<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentCards.php');
include_once(__DIR__.'/../../export/modules/CardsHandler.php');
include_once(__DIR__.'/../../export/modules/BGA/Deck.php');

class CurrentCardsTest extends TestCase{
    protected CurrentCards $sut;

    protected function setUp(): void {
        $this->mock_cards = $this->createMock(\NieuwenhovenGames\BGA\Deck::class);
        $this->sut = CurrentCards::create($this->mock_cards);
    }

    public function testGet_Hands_Array() {
        // Arrange
        $player_id = 7;
        $this->mock_cards->expects($this->exactly(4))->method('getCardsInLocation')->will($this->returnValue(['x']));
        // Act
        $hands = $this->sut->getHands($player_id);
        // Assert
        $this->assertCount(4, $hands);
    }
}
?>
