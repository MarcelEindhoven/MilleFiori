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
    }
    public function arrange($player_id, $player_position) {
        $this->mock->expects($this->exactly(1))->method('getObjectList')->will($this->returnValue(
            [0 => [Ocean::KEY_PLAYER_ID => $player_id, Ocean::KEY_PLAYER_POSITION => $player_position], 
             1 => [Ocean::KEY_PLAYER_ID => $player_id + 1, Ocean::KEY_PLAYER_POSITION=>0]]));
        $this->sut = Ocean::create($this->mock);
    }
    
    public function testGenerateFields() {
        // Arrange
        // Act
        $fields = Ocean::create($this->mock)->generateFields();
        // Assert
        $this->assertCount(21, $fields);
        $this->assertFalse(min(array_column($fields, 'LEFT')) < 0);
        //$this->assertEqualsCanonicalizing();
    }

    public function testSelectableFieldsPlus5ReturnField5() {
        // Arrange
        $this->arrange(2, 0);
        // Act
        $selectableFields = $this->sut->getSelectableFields(2, 5);
        // Assert
        $this->assertCount(1, $selectableFields);
        $this->assertEquals('5', current($selectableFields));
    }

    public function testSelectableFields20Plus5ReturnField21() {
        // Arrange
        $this->arrange(2, 20);
        // Act
        $selectableFields = $this->sut->getSelectableFields(2, 5);
        // Assert
        $this->assertCount(1, $selectableFields);
        $this->assertEquals('21', current($selectableFields));
    }

    public function testPlayerPositionDefaultZero() {
        // Arrange
        $this->arrange(0, 0);

        // Act
        $position = $this->sut->getPlayerPosition(1);
        
        // Assert
        $this->assertEquals(0, $position);
    }

    public function testPlayerPositionAdvancePositionUpdated() {
        // Arrange
        $this->arrange(2, 5);
        $this->mock->expects($this->exactly(1))->method('query');
        // Act
        $this->sut->advancePlayerPosition(2, 5);
        // Assert
    }

    public function testPlayerPositionAdvance25Position21() {
        // Arrange
        $this->arrange(2, 20);
        $this->mock->expects($this->exactly(1))->method('query')->with($this->equalTo(Ocean::UPDATE_OCEAN_POSITION . 21 . Ocean::QUERY_WHERE . 2));

        // Act
        $this->sut->advancePlayerPosition(2, 5);

        // Assert
    }
}
?>
