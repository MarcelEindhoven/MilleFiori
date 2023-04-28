<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/Robot.php');
include_once(__DIR__.'/../export/modules/PlayerRobotProperties.php');

class RobotTest extends TestCase{
    public function setup() : void {
        $this->sut = new Robot();
    }

    public function testSelectField_Empty_ReturnNull() {
        // Arrange
        // Act
        $card = $this->sut->selectField([]);
        // Assert
        $this->assertEquals(null, $card);
    }
}
?>
