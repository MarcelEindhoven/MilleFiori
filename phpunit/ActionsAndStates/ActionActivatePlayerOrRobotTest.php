<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/ActionActivatePlayerOrRobot.php');
include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');
include_once(__DIR__.'/../../export/modules/BGA/CurrentPlayerOrRobot.php');

class ActionActivatePlayerOrRobotTest extends TestCase{

    protected ActionActivatePlayerOrRobot $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(\NieuwenhovenGames\BGA\FrameworkInterfaces\GameState::class);
        $this->sut = ActionActivatePlayerOrRobot::create($this->mock_gamestate);

        $this->mock_player_or_robot = $this->createMock(\NieuwenhovenGames\BGA\CurrentPlayerOrRobot::class);
        $this->sut->setCurrentPlayerOrRobot($this->mock_player_or_robot);
    }

    public function testNextState_SelectionSimultaneousNoRobot_PlayerPlays() {
        // Arrange
        $this->arrangePlayerOrRobot(true);
        $this->sut->setCardSelectionSimultaneous(true);

        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('activatePlayerToPlayCard');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_SelectionSimultaneousRobot_RobotPlays() {
        // Arrange
        $this->arrangePlayerOrRobot(false);
        $this->sut->setCardSelectionSimultaneous(true);

        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('activateRobotToPlayCard');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_SelectionSimultaneousNoRobot_PlayerSelects() {
        // Arrange
        $this->arrangePlayerOrRobot(true);
        $this->sut->setCardSelectionSimultaneous(false);

        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('activatePlayerToSelectCard');
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_SelectionSimultaneousRobot_RobotSelects() {
        // Arrange
        $this->arrangePlayerOrRobot(false);
        $this->sut->setCardSelectionSimultaneous(false);

        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with('activateRobotToSelectCard');
        // Act
        $this->sut->nextState();
        // Assert
    }

    private function arrangePlayerOrRobot($is_player)
    {
        $this->mock_player_or_robot->expects($this->exactly(1))->method('isPlayer')->with()->will($this->returnValue($is_player));
    }
}
?>

