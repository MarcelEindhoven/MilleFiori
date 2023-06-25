<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/Action.php');

include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');

class TestAction extends Action {
    protected string $transition_name = '';

    public function setTransitionName(string $transition_name) {
        $this->transition_name = $transition_name;
        return $this;
    }

    protected function getTransitionName(): string {
        return $this->transition_name;
    }
}

class ActionTest extends TestCase{
    protected Action $sut;

    protected function setUp(): void {

    }

    protected function arrangeDefault(string $transition_name = '') {
        $this->mock_gamestate = $this->createMock(GameStateInterface::class);
        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with($transition_name);
    }

    public function testNextState_Default_TransitionEmpty() {
        // Arrange
        $this->arrangeDefault();

        $this->sut = new Action($this->mock_gamestate);
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_ChildOverridesTransition_NextStateWithThatTransition() {
        // Arrange
        $transition_name = 'x ';
        $this->arrangeDefault($transition_name);

        $this->sut = new TestAction($this->mock_gamestate);

        $this->sut->setTransitionName($transition_name);
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
