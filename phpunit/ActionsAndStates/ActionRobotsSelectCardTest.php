<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionRobotsSelectCard.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentData.php');
include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');

class ActionRobotsSelectCardTest extends TestCase{

    protected ActionRobotsSelectCard $sut;

    protected function setUp(): void {
        $this->mock_data = $this->createMock(CurrentData::class);
        $this->sut = ActionRobotsSelectCard::create($this->mock_data);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);

        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameStateInterface::class);
        $this->sut->setGameState($this->mock_gamestate);
    }

    public function testExecute_2Players_DataCards() {
        // Arrange
        $robert_ids = [4, 5];
        $this->mock_data->expects($this->exactly(1))->method('getRobotIDs')->will($this->returnValue($robert_ids));
        $this->mock_data->expects($this->exactly(2))->method('getHand')->will($this->returnValue(['id' => 1]));
        // Act
        $this->sut->execute();
        // Assert
    }

    public function testNextState_SelectionSimultaneousNoRobot_selectCardMultipleActivePlayers() {
        // Arrange
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState');
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
