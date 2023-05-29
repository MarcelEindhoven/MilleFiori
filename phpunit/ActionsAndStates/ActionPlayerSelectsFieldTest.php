<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionPlayerSelectsField.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateOcean.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');
include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');
include_once(__DIR__.'/../../export/modules/BGA/NotifyInterface.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class ActionPlayerSelectsFieldTest extends TestCase{

    protected ActionPlayerSelectsField $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut = ActionPlayerSelectsField::create($this->mock_gamestate);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);

        $this->mock_data_handler = $this->createMock(UpdateOcean::class);
        $this->sut->setFieldSelectionHandler($this->mock_data_handler);

        $this->mock_notify_handler = $this->createMock(\NieuwenhovenGames\BGA\NotifyInterface::class);
        $this->sut->setNotifyHandler($this->mock_notify_handler);

        $this->mock_event_handler = $this->createMock(\NieuwenhovenGames\BGA\EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_event_handler);

        $this->player_id = 55;
        $this->field_id = 'field_ocean_5';
        $this->sut->setPlayerAndField($this->player_id, $this->field_id);
    }

    public function testExecute_Always_SelectableFieldIDsEmpty() {
        // Arrange
        $this->mock_notify_handler->expects($this->exactly(1))->method('notifyPlayer')->with($this->player_id, 'selectableFields', '', ['selectableFields' => []]);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute_Always_EmptyPlayedHand() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('emptyPlayedHand');
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute_Always_Subscription() {
        // Arrange
        $this->mock_event_handler->expects($this->exactly(1))->method('on')->with('SelectExtraCard', [$this->sut, 'selectExtraCard']);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute_Always_playerSelectsField() {
        // Arrange
        $this->mock_data_handler->expects($this->exactly(1))->method('playerSelectsField')->with($this->player_id, $this->field_id);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testState_NoExtraCard_TurnEnded() {
        // Arrange
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('turnEnded');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testState_ExtraCard_TurnEnded() {
        // Arrange
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('selectExtraCard');
        $this->sut->selectExtraCard([]);
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
