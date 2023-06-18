<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class EventReceiver {
    public function receive($event) {}
}

class EventEmitterTest extends TestCase{
    protected EventEmitter $sut;

    protected function setUp(): void {
        $this->channel = 'channel';
        $this->event = [];

        $this->mock_receiver = $this->createMock(EventReceiver::class);
        $this->callable = [$this->mock_receiver, 'receive'];

        $this->sut = new EventEmitter();
    }

    public function testEmit_NoSubscribers_NothingHappens() {
        // Arrange
        $this->mock_receiver->expects($this->exactly(0))->method('receive');
        // Act
        $this->sut->emit($this->channel, $this->event);
        // Assert
    }

    public function testEmit_1SubscribersDifferentChannel_NothingHappens() {
        // Arrange
        $this->mock_receiver->expects($this->exactly(0))->method('receive');
        $this->sut->on('Hello world', $this->callable);
        // Act
        $this->sut->emit($this->channel, $this->event);
        // Assert
    }

    public function testEmit_1SubscribersSameChannel_1Receive() {
        // Arrange
        $this->mock_receiver->expects($this->exactly(1))->method('receive');
        $this->sut->on($this->channel, $this->callable);
        // Act
        $this->sut->emit($this->channel, $this->event);
        // Assert
    }

    public function testEmit_2SubscribersSameChannel_2Receive() {
        // Arrange
        $this->mock_receiver->expects($this->exactly(2))->method('receive');
        $this->sut->on($this->channel, $this->callable);
        $this->sut->on($this->channel, $this->callable);
        // Act
        $this->sut->emit($this->channel, $this->event);
        // Assert
    }

    public function testOnce_1Emit_1Receive() {
        // Arrange
        $this->mock_receiver->expects($this->exactly(1))->method('receive');
        $this->sut->once($this->channel, $this->callable);
        // Act
        $this->sut->emit($this->channel, $this->event);
        // Assert
    }

    public function testOnce_2Emit_1Receive() {
        // Arrange
        $this->mock_receiver->expects($this->exactly(1))->method('receive');
        $this->sut->once($this->channel, $this->callable);
        // Act
        $this->sut->emit($this->channel, $this->event);
        $this->sut->emit($this->channel, $this->event);
        // Assert
    }

    public function testOff_Always_NoReceive() {
        // Arrange
        $this->mock_receiver->expects($this->exactly(0))->method('receive');
        $this->sut->once($this->channel, $this->callable);
        // Act
        $this->sut->off($this->channel, $this->callable);
        $this->sut->emit($this->channel, $this->event);
        // Assert
    }
}
?>
