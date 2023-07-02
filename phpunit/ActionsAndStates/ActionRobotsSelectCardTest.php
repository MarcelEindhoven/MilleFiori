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
include_once(__DIR__.'/../../export/modules/ActionsAndStates/Robot.php');
include_once(__DIR__.'/../../export/modules/ActionsAndStates/RobotHandler.php');
include_once(__DIR__.'/../../export/modules/BGA/GameState.php');

class ActionRobotsSelectCardTest extends TestCase{

    protected ActionRobotsSelectCard $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\GameState::class);
        $this->sut = ActionRobotsSelectCard::create($this->mock_gamestate);

        $this->mock_cards = $this->createMock(UpdateCards::class);
        $this->sut->setCardsHandler($this->mock_cards);

        $this->mock_robots = $this->createMock(RobotHandler::class);
        $this->sut->setRobotHandler($this->mock_robots);
    }

    public function testExecute_2Players_DataCards() {
        // Arrange
        $this->mock_robot = $this->createMock(Robot::class);
        $this->mock_robot->expects($this->exactly(2))->method('selectCard')->will($this->returnValue([CurrentData::CARD_KEY_ID => 3]));
        $this->mock_robot->expects($this->exactly(2))->method('getPlayerID')->will($this->returnValue(5));
        $robert_ids = [4, 5];
        $this->mock_robots->expects($this->exactly(1))->method('getRobots')->will($this->returnValue([$this->mock_robot, $this->mock_robot]));
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
