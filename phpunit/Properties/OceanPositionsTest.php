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
        $this->mock_array = $this->createMock(\ArrayAccess::class);
        //$this->mock_array->expects($this->exactly(1))->method('offsetGet')->withConsecutive([$this->player_id])->will($this->returnValue(OceanPositionsTest::DEFAULT_POSITION));
        $this->sut = OceanPositions::CreateFromPlayerProperties(OceanPositionsTest::DEFAULT_POSITION_DATA);
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
        $position = $this->sut[$this->player_id];
        // Assert
        $this->assertEquals(OceanPositionsTest::DEFAULT_POSITION, $position);
    }

    public function testSet_KnownPlayer_ReturnSetValue() {
        // Arrange
        $value = 7;
        // Act
        $this->sut[$this->player_id] = $value;
        // Assert
        $this->assertEquals($value, $this->sut[$this->player_id]);
    }
}
?>
