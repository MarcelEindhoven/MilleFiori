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
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut = ActionNewHand::create($this->mock_gamestate);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);
    }

    public function testExecute_2Players_DataCards() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('dealNewHands');
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testNextState_SelectionSimultaneousNoRobot_selectCardMultipleActivePlayers() {
        // Arrange
        $this->sut->setCardSelectionSimultaneous(true);

        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->withConsecutive(['selectCardMultipleActivePlayers']);
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_SelectionSingle_selectCardSingleActivePlayer() {
        // Arrange
        $this->sut->setCardSelectionSimultaneous(false);

        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->withConsecutive(['selectCardSingleActivePlayer']);
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
