<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionPlayerWillPlayCard.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');
include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');

class ActionPlayerWillPlayCardTest extends TestCase{

    protected ActionPlayerWillPlayCard $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut = ActionPlayerWillPlayCard::create($this->mock_gamestate);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);

        $this->mock_data_handler = $this->createMock(CurrentData::class);
        $this->sut->setDataHandler($this->mock_data_handler);

        $this->player_id = 55;
        $this->sut->setCurrentPlayerID($this->player_id);
    }

    public function testExecute_2Players_DataCards() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('playSelectedCard')->with($this->player_id);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testNextState_SelectionSimultaneousNoData_selectCardMultipleActivePlayers() {
        // Arrange
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState');
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>