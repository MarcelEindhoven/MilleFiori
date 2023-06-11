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

        $this->mock_player_or_robot = $this->createMock(\NieuwenhovenGames\BGA\CurrentPlayerOrRobot::class);
        $this->sut->setCurrentPlayerOrRobot($this->mock_player_or_robot);
    }

    public function testExecute_Always_NextPlayer() {
        // Arrange
        $this->mock_player_or_robot->expects($this->exactly(1))->method('nextPlayerOrRobot');
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testNextState_1PlayerHasAdditionalHandCard_turnEnded() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('haveAllPlayersSameCardCount')->will($this->returnValue(false));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('turnEnded');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_PlayersSameNumberOfCards_RoundEnded() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('haveAllPlayersSameCardCount')->will($this->returnValue(true));
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('roundEnded');
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>

