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

        $this->mock_data_handler = $this->createMock(CurrentData::class);
        $this->sut->setDataHandler($this->mock_data_handler);

        $this->mock_notify_handler = $this->createMock(\NieuwenhovenGames\BGA\NotifyInterface::class);
        $this->sut->setNotifyHandler($this->mock_notify_handler);

        $this->mock_event_handler = $this->createMock(\NieuwenhovenGames\BGA\EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_event_handler);

        $this->player_id = 55;
        $this->sut->setCurrentPlayerID($this->player_id);
    }

    public function testExecute_PlayerID_SelectableFieldIDs() {
        // Arrange
        $this->mock_notify_handler->expects($this->exactly(1))->method('notifyPlayer')->with($this->player_id, 'selectableFields', '', ['selectableFields' => []]);
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testExecute_PlayerID_EmptyPlayedHand() {
        // Arrange
        $this->mock_cards->expects($this->exactly(1))->method('emptyPlayedHand');
        // Act
        $this->sut->execute();
        // Assert
    }
}
?>
