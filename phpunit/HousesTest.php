<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../export/modules/Houses.php');

include_once(__DIR__.'/../export/modules/Categories.php');

class HousesTest extends TestCase{
    public function setup() : void {
        $this->mock = $this->createMock(Categories::class);
        $this->sut = Houses::create($this->mock);
    }

    public function testReward_Zero_2Points() {
        // Arrange
        // Act
        $reward = $this->sut->getReward(2, 0);
        // Assert
        $this->assertEquals(2, $reward['points']);
    }

    public function testReward_SingleLast_3Points() {
        // Arrange
        // Act
        $reward = $this->sut->getReward(2, 19);
        // Assert
        $this->assertEquals(3, $reward['points']);
    }

    public function testReward_Overflow_Exception() {
        // Arrange
        $this->expectWarning();
        // Act
        $reward = $this->sut->getReward(2, 20);
        // Assert
        $this->assertEquals(3, $reward['points']);
    }

    public function testSelectableFields_OtherCategory_Empty() {
        // Arrange
        // Act
        $selectable_fields = $this->sut->getSelectableFieldIDs(2, 200);
        // Assert
        $this->assertEquals([], $selectable_fields);
    }
}
?>
