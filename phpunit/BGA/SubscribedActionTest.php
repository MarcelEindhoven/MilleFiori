<?php
namespace NieuwenhovenGames\BGA;
/**
 * Check event subscription and unsubscription
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/SubscribedAction.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/GameState.php');

class TestSubscribedAction extends SubscribedAction {
}

class SubscribedActionTest extends TestCase{
    protected SubscribedAction $sut;

    protected function setUp(): void {
        $this->mock_gamestate = $this->createMock(FrameworkInterfaces\GameState::class);
        $this->sut = new TestSubscribedAction($this->mock_gamestate);

        $this->mock_emitter = $this->createMock(EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_emitter);
    }

    public function testSubscribe_Always_EmitterOnCalled() {
        // Arrange
        $this->event_name = 'event';
        $this->method_name = 'handle_event';

        $this->mock_emitter->expects($this->exactly(1))->method('on')->with($this->event_name, [$this->sut, $this->method_name]);
        // Act
        $this->sut->subscribe($this->event_name, $this->method_name);
        // Assert
    }

    public function testNextState_Subscribed_EmitterOffCalled() {
        // Arrange
        $this->event_name = 'event';
        $this->method_name = 'handle_event';

        $this->mock_emitter->expects($this->exactly(1))->method('off')->with($this->event_name, [$this->sut, $this->method_name]);

        $this->sut->subscribe($this->event_name, $this->method_name);
        // Act
        $this->sut->nextState();
        // Assert
    }

    public function testNextState_NotSubscribed_EmitterOffNotCalled() {
        // Arrange
        $this->mock_emitter->expects($this->exactly(0))->method('off');
        // Act
        $this->sut->nextState();
        // Assert
    }
}
?>
