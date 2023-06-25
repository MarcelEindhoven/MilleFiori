<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/SubscribedAction.php');

include_once(__DIR__.'/../../export/modules/BGA/GameStateInterface.php');

class TestSubscribedAction extends SubscribedAction {
    protected string $transition_name = '';

    public function setTransitionName(string $transition_name) {
        $this->transition_name = $transition_name;
        return $this;
    }

    protected function getTransitionName(): string {
        return $this->transition_name;
    }
}

class SubscribedActionTest extends TestCase{
    protected SubscribedAction $sut;

    protected function setUp(): void {
        $this->sut = new TestSubscribedAction();

        $this->mock_emitter = $this->createMock(EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_emitter);
    }

    protected function arrangeDefault(string $transition_name = '') {
        $this->mock_gamestate = $this->createMock(GameStateInterface::class);
        $this->sut->setGameState($this->mock_gamestate);

        $this->mock_gamestate->expects($this->exactly(1))->method('nextState')->with($transition_name);
    }

    public function testNextState_Default_TransitionEmpty() {
        // Arrange
        $this->sut = new SubscribedAction();

        $this->arrangeDefault();
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_ChildOverridesTransition_NextStateWithThatTransition() {
        // Arrange

        $transition_name = 'x ';
        $this->sut->setTransitionName($transition_name);

        $this->arrangeDefault($transition_name);
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
