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
include_once(__DIR__.'/../export/modules/PlayerProperties.php');

class RobotTest extends TestCase{
    public function setup() : void {
        $this->sut = new Robot();
    }

    public function testCreate_Empty_NoRobots() {
        // Arrange
        // Act
        $robots = Robot::create([]);
        // Assert
        $this->assertCount(0, $robots);
    }

    public function testCreate_2Properties_Robots() {
        // Arrange
        $robot_id = 2;
        $robot_list = [0 => [PlayerProperties::KEY_ID => $robot_id, PlayerProperties::KEY_POSITION => 0], 
        1 => [PlayerProperties::KEY_ID => $robot_id + 1, PlayerProperties::KEY_POSITION => 0]];
        // Act
        $robots = Robot::create($robot_list);
        // Assert
        $this->assertCount(2, $robots);
        $this->assertEquals($robot_id, $robots[0]->getPlayerID());
    }

    public function testSelectCard_Empty_ReturnNull() {
        // Arrange
        // Act
        $card = $this->sut->selectCard([]);
        // Assert
        $this->assertEquals(null, $card);
    }

    public function testSelectCard_Single_ReturnCardID() {
        // Arrange
        $expectedCardID = 'card';
        // Act
        $card = $this->sut->selectCard([$expectedCardID]);
        // Assert
        $this->assertEquals($expectedCardID, $card);
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
