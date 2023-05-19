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
        $this->sut->on('Hello world', [$this->mock_receiver, 'receive']);
        // Act
        $this->sut->emit($this->channel, $this->event);
        // Assert
    }
}
?>
