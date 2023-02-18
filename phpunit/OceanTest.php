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

class OceanTest extends TestCase{
    public function setup() : void {
        $this->ocean = new Ocean();
    }

    public function testGenerateFields() {
        // Arrange
        // Act
        $fields = $this->ocean->generateFields();
        // Assert
        $this->assertCount(21, $fields);
        $this->assertFalse(min(array_column($fields, 'LEFT')) < 0);
        //$this->assertEqualsCanonicalizing();
    }

    public function testPlayerPositionDefaultZero() {
        // Arrange
        // Act
        $position = $this->ocean->getPlayerPosition(1);
        // Assert
        $this->assertEquals(0, $position);
    }

    public function testPlayerPositionAdvancePositionUpdated() {
        // Arrange
        // Act
        $this->ocean->advancePlayerPosition(2, 5);
        // Assert
        $this->assertEquals(0, $this->ocean->getPlayerPosition(1));
        $this->assertEquals(5, $this->ocean->getPlayerPosition(2));
    }

    public function testPlayerPositionAdvance25Position21() {
        // Arrange
        // Act
        $this->ocean->advancePlayerPosition(2, 5);
        $this->ocean->advancePlayerPosition(2, 5);
        $this->ocean->advancePlayerPosition(2, 5);
        $this->ocean->advancePlayerPosition(2, 5);
        $this->ocean->advancePlayerPosition(2, 5);
        // Assert
        $this->assertEquals(0, $this->ocean->getPlayerPosition(1));
        $this->assertEquals(21, $this->ocean->getPlayerPosition(2));
    }
}
?>
