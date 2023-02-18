<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/Ocean.php');

include_once(__DIR__.'/../export/modules/BGA/DatabaseInterface.php');

class OceanTest extends TestCase{
    public function setup() : void {
        $this->mock = $this->createMock(\NieuwenhovenGames\BGA\DatabaseInterface::class);
        $this->sut = Ocean::create($this->mock);
    }

    public function testGenerateFields() {
        // Arrange
        // Act
        $fields = $this->sut->generateFields();
        // Assert
        $this->assertCount(21, $fields);
        $this->assertFalse(min(array_column($fields, 'LEFT')) < 0);
        //$this->assertEqualsCanonicalizing();
    }

    public function testPlayerPositionDefaultZero() {
        // Arrange
        $this->mock->expects($this->exactly(1))->method('getObjectList')->will($this->returnValue([['ocean_position'=>0]]));

        // Act
        $position = $this->sut->getPlayerPosition(1);
        
        // Assert
        $this->assertEquals(0, $position);
    }

    public function testPlayerPositionAdvancePositionUpdated() {
        // Arrange
        $this->mock->expects($this->exactly(1))->method('getObjectList')->will($this->returnValue([['ocean_position'=>5]]));
        $this->mock->expects($this->exactly(1))->method('query');
        // Act
        $this->sut->advancePlayerPosition(2, 5);
        // Assert
    }

    public function testPlayerPositionAdvance25Position21() {
        // Arrange
        $this->mock->expects($this->exactly(1))->method('getObjectList')->will($this->returnValue([['ocean_position'=>20]]));
        $this->mock->expects($this->exactly(1))->method('query')->with($this->equalTo(Ocean::UPDATE_OCEAN_POSITION . 21 . Ocean::QUERY_WHERE . 2));

        // Act
        $this->sut->advancePlayerPosition(2, 5);

        // Assert
    }
}
?>
