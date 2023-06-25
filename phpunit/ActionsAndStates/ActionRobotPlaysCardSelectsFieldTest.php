<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionRobotPlaysCardSelectsField.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateOcean.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');
include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');
include_once(__DIR__.'/../../export/modules/BGA/NotifyInterface.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class ActionRobotPlaysCardSelectsFieldTest extends TestCase{

    protected ActionRobotPlaysCardSelectsField $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut = ActionRobotPlaysCardSelectsField::create($this->mock_gamestate);

        $this->mock_emitter = $this->createMock(\NieuwenhovenGames\BGA\EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_emitter);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);

        $this->mock_data_handler = $this->createMock(CurrentData::class);
        $this->sut->setDataHandler($this->mock_data_handler);

        $this->mock_update_handler = $this->createMock(UpdateOcean::class);
        $this->sut->setFieldSelectionHandler($this->mock_update_handler);

        $this->player_id = 55;
        $this->field_id = 'field_ocean_5';

        $this->mock_robot = $this->createMock(Robot::class);
        $this->sut->setRobot($this->mock_robot);
    }

    protected function arrangeExecute() {
        $this->mock_emitter->expects($this->exactly(1))->method('on')->with('selectExtraCard', [$this->sut, 'select_extra_card']);

        $this->mock_robot->method('getPlayerID')->will($this->returnValue($this->player_id));

        $expected_field_ids = [$this->field_id];
        $this->mock_data_handler->expects($this->exactly(1))->method('getSelectableFieldIDs')->with($this->player_id)->will($this->returnValue($expected_field_ids));

        $this->mock_robot->method('selectField')->with($expected_field_ids)->will($this->returnValue($this->field_id));
    }

    public function testExecute_Always_PlaySelectedCard() {
        // Arrange
        $this->arrangeExecute();
        $this->mock_cards->expects($this->exactly(1))->method('playSelectedCard')->with($this->player_id);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute_Always_SelectableFieldIDs() {
        // Arrange
        $this->arrangeExecute();
        $expected_field_ids = [$this->field_id];
        $this->mock_data_handler->expects($this->exactly(1))->method('getSelectableFieldIDs')->with($this->player_id)->will($this->returnValue($expected_field_ids));
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute_Always_EmptyPlayedHand() {
        // Arrange
        $this->arrangeExecute();
        $this->mock_cards->expects($this->exactly(1))->method('emptyPlayedHand');
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute_Always_playerSelectsField() {
        // Arrange
        $this->arrangeExecute();
        $this->mock_update_handler->expects($this->exactly(1))->method('playerSelectsField')->with($this->player_id, $this->field_id);
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

    public function testState_ExtraCard_TransitionExtraCard() {
        // Arrange
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('selectExtraCard');
        $this->sut->selectExtraCard([]);
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
