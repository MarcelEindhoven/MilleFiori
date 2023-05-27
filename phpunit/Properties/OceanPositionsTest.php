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
    const DEFAULT_PLAYER_ID = 3;
    const DEFAULT_POSITION = 5;
    const DEFAULT_POSITION_DATA = [OceanPositionsTest::DEFAULT_PLAYER_ID => [Ocean::KEY_PLAYER_POSITION => OceanPositionsTest::DEFAULT_POSITION]];
    

    public function setup() : void {
        $this->player_id = OceanPositionsTest::DEFAULT_PLAYER_ID;
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
        $this->mock_event_handler->expects($this->exactly(0))->method('emit');
        // Act
        $position = $this->sut[$this->player_id];
        // Assert
        $this->assertEquals(OceanPositionsTest::DEFAULT_POSITION, $position);
    }

    public function testSet_KnownPlayer_Emit() {
        // Arrange
        $value = 7;
        $event_position = ['player_id' => $this->player_id, 'property_name' => Ocean::KEY_PLAYER_POSITION, 'property_value' => $value];
        $this->mock_event_handler->expects($this->exactly(1))->method('emit')->withConsecutive(['PlayerPropertyUpdated', $event_position]);
        // Act
        $this->sut[$this->player_id] = $value;
        // Assert
        $this->assertEquals($value, $this->sut[$this->player_id]);
    }
}
?>
