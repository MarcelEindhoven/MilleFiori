<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionEndPlayerTurn.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');

class ActionEndPlayerTurnTest extends TestCase{

    protected ActionEndPlayerTurn $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut = ActionEndPlayerTurn::create($this->mock_gamestate);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);
    }

    public function testNextState_1PlayerHasAdditionalHandCard_turnEnded() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('getNumberPlayerCards')->will($this->returnValue(5));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('turnEnded');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_1PlayerHasSelectedCard_turnEnded() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('getNumberPlayerCards')->will($this->returnValue(4));
        $this->mock_cards->expects($this->exactly(1))->method('getNumberSelectedCards')->will($this->returnValue(1));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('turnEnded');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_SufficientPlayerCardsSelectionSimultaneous_RoundEndedSelectionSimultaneous() {
        // Arrange
        $this->mock_cards->expects($this->exactly(2))->method('getNumberPlayerCards')->will($this->returnValue(8));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('roundEndedMultipleActivePlayers');
        $this->sut->setCardSelectionSimultaneous(true);
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_SufficientPlayerCardsSelectionNotSimultaneous_RoundEndedSelectionNotSimultaneous() {
        // Arrange
        $this->mock_cards->expects($this->exactly(2))->method('getNumberPlayerCards')->will($this->returnValue(8));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('roundEndedSingleActivePlayer');
        $this->sut->setCardSelectionSimultaneous(false);
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_SufficientDeckCards_HandEnded() {
        // Arrange
        $this->mock_cards->expects($this->exactly(2))->method('getNumberPlayerCards')->will($this->returnValue(4));
        $this->mock_cards->expects($this->exactly(1))->method('getNumberDeckCards')->will($this->returnValue(20));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('handEnded');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_DeckEmpty_GameEnded() {
        // Arrange
        $this->mock_cards->expects($this->exactly(2))->method('getNumberPlayerCards')->will($this->returnValue(4));
        $this->mock_cards->expects($this->exactly(1))->method('getNumberDeckCards')->will($this->returnValue(10));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('gameEnded');
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>

