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
    public function testGenerateFields() {
        // Arrange
        $layout = new Ocean();
        // Act
        $fields = $layout->generateFields();
        // Assert
        $this->assertCount(21, $fields);
        $this->assertFalse(min(array_column($fields, 'LEFT')) < 0);
        //$this->assertEqualsCanonicalizing();
    }
}
?>
