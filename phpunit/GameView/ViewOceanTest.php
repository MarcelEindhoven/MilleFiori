<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameView/ViewOcean.php');

class ViewOceanTest extends TestCase{
    public function setup() : void {
        $this->sut = new ViewOcean();
    }

    public function testFields_Generate_21Fields() {
        // Arrange
        // Act
        $fields = $this->sut->generateFields();
        // Assert
        $this->assertCount(21, $fields);
    }
}
?>
