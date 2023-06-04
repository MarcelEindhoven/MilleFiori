<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionEndRound.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');

class ActionEndRoundTest extends TestCase{

    protected ActionEndRound $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut = ActionEndRound::create($this->mock_gamestate);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);
    }

    public function testExecute_SufficientPlayerCards_SwapHands() {
        // Arrange
        $this->arrangeNumberPlayerCards(8);
        $this->mock_cards->expects($this->exactly(1))->method('swapHands');
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testNextState_SufficientPlayerCardsSelectionSimultaneous_RoundEndedSelectionSimultaneous() {
        // Arrange
        $this->arrangeNumberPlayerCards(8);
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('roundEndedMultipleActivePlayers');
        $this->sut->setCardSelectionSimultaneous(true);
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_SufficientPlayerCardsSelectionNotSimultaneous_RoundEndedSelectionNotSimultaneous() {
        // Arrange
        $this->arrangeNumberPlayerCards(8);
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('roundEndedSingleActivePlayer');
        $this->sut->setCardSelectionSimultaneous(false);
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_SufficientDeckCards_HandEnded() {
        // Arrange
        $this->arrangeNumberPlayerCards(4);
        $this->mock_cards->expects($this->exactly(1))->method('getNumberDeckCards')->will($this->returnValue(20));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('handEnded');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_DeckEmpty_GameEnded() {
        // Arrange
        $this->arrangeNumberPlayerCards(4);
        $this->mock_cards->expects($this->exactly(1))->method('getNumberDeckCards')->will($this->returnValue(0));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('gameEnded');
        // Act
        $this->sut->nextState();
        // Assert
    }

    protected function arrangeNumberPlayerCards($number_cards) {
        $this->mock_cards->expects($this->exactly(1))->method('getNumberPlayerCards')->will($this->returnValue($number_cards));
    }

}
?>

