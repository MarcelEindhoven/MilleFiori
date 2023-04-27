<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionNewHand.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');
include_once(__DIR__.'/../../export/modules/CardsHandler.php');

class ActionNewHandTest extends TestCase{

    protected ActionNewHand $sut;

    protected function setUp(): void {
        $this->mock_data = $this->createMock(CurrentData::class);
        $this->sut = ActionNewHand::create($this->mock_data);

        $this->mock_cards = $this->createMock(CardsHandler::class);
        $this->sut->setCardsHandler($this->mock_cards);
    }

    public function testProperties_GetPlayer_GetBucket() {
        // Arrange
        $player_ids = [5, 55];
        $this->mock_data->expects($this->exactly(1))->method('getPlayerIDs')->willReturnOnConsecutiveCalls($player_ids);

        $this->mock_cards->expects($this->exactly(2))->method('moveHandToSideboard')->withConsecutive([$player_ids[0]], [$player_ids[1]]);
        $this->mock_cards->expects($this->exactly(2))->method('dealNewHand')->withConsecutive();
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        // Act
        $this->sut->execute();
        // Assert
    }
}
?>
