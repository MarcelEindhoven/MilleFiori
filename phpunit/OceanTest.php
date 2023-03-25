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

include_once(__DIR__.'/../export/modules/PlayerProperties.php');

class OceanTest extends TestCase{
    public function setup() : void {
        $this->mock = $this->createMock(PlayerProperties::class);
    }

    private function arrange($player_id, $player_position) {
        $this->mock->expects($this->exactly(1))->method('getPropertiesPlayersPlusRobots')->will($this->returnValue(
            [0 => [Ocean::KEY_PLAYER_ID => $player_id, Ocean::KEY_PLAYER_POSITION => $player_position], 
             1 => [Ocean::KEY_PLAYER_ID => $player_id + 1, Ocean::KEY_PLAYER_POSITION=>0]]));
        $this->sut = Ocean::create($this->mock);
    }

    private function expectNoUpdate() {
        $this->mock->expects($this->exactly(0))->method('setOceanPosition');
    }

    private function expectSingleUpdate($player_id, $position) {
        $this->mock->expects($this->exactly(1))->method('setOceanPosition')->with($this->equalTo($player_id), $this->equalTo($position));
    }

    public function testTooltips_Get_Array() {
        // Arrange
        $this->arrange(2, 0);
        // Act
        $tooltips = $this->sut->getTooltips();
        // Assert
        $this->assertCount(count(Ocean::PLACES_PER_CARD), $tooltips);
    }

    public function testReward_Zero_NoReward() {
        // Arrange
        $this->arrange(2, 0);
        // Act
        $reward = $this->sut->getReward(2, 0);
        // Assert
        $this->assertEquals(['points' => 0], $reward);
    }

    public function testReward_One_OnePoint() {
        // Arrange
        $this->arrange(2, 0);
        // Act
        $reward = $this->sut->getReward(2, 1);
        // Assert
        $this->assertEquals(1, $reward['points']);
    }

    public function testReward_OneTooMuch_Exception() {
        // Arrange
        $this->arrange(2, 0);
        $this->expectWarning();
        // Act
        $reward = $this->sut->getReward(2, Ocean::NUMBER_FIELDS);
        // Assert
    }

    public function testReward_MaximumID_Points() {
        // Arrange
        $this->arrange(2, 0);
        // Act
        $reward = $this->sut->getReward(2, Ocean::NUMBER_FIELDS - 1);
        // Assert
        $this->assertEquals(10, $reward['points']);
    }

    public function testReward_MaximumIDNoMove_NoReward() {
        // Arrange
        $this->arrange(2, Ocean::NUMBER_FIELDS - 1);
        // Act
        $reward = $this->sut->getReward(2, Ocean::NUMBER_FIELDS - 1);
        // Assert
        $this->assertEquals(0, $reward['points']);
    }
    
    public function testGenerateFields() {
        // Arrange
        // Act
        $this->arrange(2, 0);
        $fields = $this->sut->generateFields();
        // Assert
        $this->assertCount(21, $fields);
        $this->assertFalse(min(array_column($fields, 'LEFT')) < 0);
        //$this->assertEqualsCanonicalizing();
    }

    public function testSelectableFieldsPlus5ReturnField5() {
        // Arrange
        $this->arrange(2, 0);
        // Act
        $selectableFields = $this->sut->getSelectableFields(2, 4);
        // Assert
        $this->assertCount(1, $selectableFields);
        $this->assertEquals('5', current($selectableFields));
    }

    public function testSelectableFields_17Plus5_ReturnFieldMaximum() {
        // Arrange
        $this->arrange(2, 17);
        // Act
        $selectableFields = $this->sut->getSelectableFields(2, 109);
        // Assert
        $this->assertCount(1, $selectableFields);
        $this->assertEquals(Ocean::NUMBER_FIELDS - 1, current($selectableFields));
    }

    public function testPlayerPositionDefaultZero() {
        // Arrange
        $this->arrange(0, 0);

        // Act
        $position = $this->sut->getPlayerPosition(1);
        
        // Assert
        $this->assertEquals(0, $position);
    }

    public function testPlayerPositionSetSamePositionNotUpdated() {
        // Arrange
        $player_id = 2;
        $this->arrange($player_id, 5);
        $this->expectNoUpdate();
        
        // Act
        $this->sut->setPlayerPosition($player_id, 5);
        // Assert
        $this->assertEquals(5, $this->sut->getPlayerPosition($player_id));
    }

    public function testPlayerPositionSetPreviousPositionNotUpdated() {
        // Arrange
        $player_id = 2;
        $this->arrange($player_id, 5);
        $this->expectNoUpdate();
        // Act
        $this->sut->setPlayerPosition($player_id, 4);
        // Assert
        $this->assertEquals(5, $this->sut->getPlayerPosition($player_id));
    }

    public function testPlayerPositionSetDifferentPositionUpdated() {
        // Arrange
        $player_id = 2;
        $position = 6;
        $this->arrange($player_id, 5);
        $this->expectSingleUpdate($player_id, $position);
        // Act
        $this->sut->setPlayerPosition($player_id, $position);
        // Assert
        $this->assertEquals($position, $this->sut->getPlayerPosition($player_id));
    }
}
?>
