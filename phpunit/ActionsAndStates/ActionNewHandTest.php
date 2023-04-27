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
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');
include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');

class ActionNewHandTest extends TestCase{

    protected ActionNewHand $sut;

    protected function setUp(): void {
        $this->mock_data = $this->createMock(CurrentData::class);
        $this->sut = ActionNewHand::create($this->mock_data);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);

        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut->setGameState($this->mock_gamestate);
    }

    public function testExecute_2Players_DataCards() {
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

    public function testNextState_SelectionSimultaneousNoRobot_selectCardMultipleActivePlayers() {
        // Arrange
        $player_ids = [5, 55];
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->withConsecutive(['selectCardMultipleActivePlayers']);
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
