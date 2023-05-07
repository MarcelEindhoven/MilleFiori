<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/GameView/ViewHouses.php');

class ViewHousesTest extends TestCase{
    public function setup() : void {
        $this->sut = new ViewHouses();
    }

    public function testFields_Generate_20Fields() {
        // Arrange
        // Act
        $fields = $this->sut->generateFields();
        // Assert
        $this->assertCount(20, $fields);
    }
}
?>
