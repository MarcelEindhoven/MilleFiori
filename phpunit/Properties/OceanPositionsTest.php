<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/Properties/OceanPositions.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class OceanPositionsTest extends TestCase{
    const DEFAULT_POSITION_DATA = [3 => [Ocean::KEY_PLAYER_POSITION => 5]];
    

    public function setup() : void {
        $this->sut = OceanPositions::CreateFromPlayerProperties(OceanPositionsTest::DEFAULT_POSITION_DATA);

        $this->mock_event_handler = $this->createMock(\NieuwenhovenGames\BGA\EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_event_handler);
    }

    public function testGet_UnknownPlayer_Exception() {
        // Arrange
        $this->expectWarning();
        // Act
        $dummy = $this->sut[1];
        // Assert
    }

    public function testGet_KnownPlayer_Position5() {
        // Arrange
        // Act
        $position = $this->sut[3];
        // Assert
        $this->assertEquals(5, $position);
    }
}
?>
