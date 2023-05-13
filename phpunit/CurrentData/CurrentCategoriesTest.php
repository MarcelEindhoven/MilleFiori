<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentCategories.php');
include_once(__DIR__.'/../../export/modules/CurrentData/CurrentCategory.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class CurrentCategoriesTest extends TestCase{
    const DEFAULT_SELECTABLE_FIELD_IDS = ['field_ocean_6'];

    protected CurrentCategories $sut;

    protected function setUp(): void {
        $this->mock_category1 = $this->createMock(CurrentCategory::class);
        $this->mock_category2 = $this->createMock(CurrentCategory::class);
        $this->player_id = 7;
        $this->sut = new CurrentCategories();
        $this->sut->setCategories([$this->mock_category1, $this->mock_category2]);
    }

    public function testSelectableFieldIDs_AllCategoriesEmpty_ReturnsEmptyArray() {
        // Arrange
        $card_type = 5;
        $this->mock_category1->expects($this->exactly(1))->method('getSelectableFieldIDs')->will($this->returnValue([]));
        // Act
        $data = $this->sut->getSelectableFieldIDs($this->player_id, $card_type);
        // Assert
        $this->assertEquals([], $data);
    }

    public function testSelectableFieldIDs_BothCategoriesTwo_ReturnsArray4() {
        // Arrange
        $card_type = 5;
        $field1 = '1';
        $field2 = '2';
        $field3 = '3';
        $field4 = '4';
        $this->mock_category1->expects($this->exactly(1))->method('getSelectableFieldIDs')->with($this->player_id, $card_type)->will($this->returnValue([$field4, $field1]));
        $this->mock_category2->expects($this->exactly(1))->method('getSelectableFieldIDs')->with($this->player_id, $card_type)->will($this->returnValue([$field3, $field2]));
        // Act
        $data = $this->sut->getSelectableFieldIDs($this->player_id, $card_type);
        // Assert
        $this->assertEqualsCanonicalizing([$field1, $field2, $field3, $field4], $data);
    }

    public function testReward_AllCategoriesEmpty_ReturnsEmptyArray() {
        // Arrange
        $field = 5;
        $this->mock_category1->expects($this->exactly(1))->method('getReward')->with($this->player_id, $field)->will($this->returnValue([]));
        // Act
        $data = $this->sut->getReward($this->player_id, $field);
        // Assert
        $this->assertEquals([], $data);
    }

    public function testReward_1CategoriesReward_ReturnsReward() {
        // Arrange
        $field = 5;
        $reward = ['points' => 5, 'extra_card' => true];
        $this->mock_category1->expects($this->exactly(1))->method('getReward')->with($this->player_id, $field)->will($this->returnValue($reward));
        // Act
        $data = $this->sut->getReward($this->player_id, $field);
        // Assert
        $this->assertEquals($reward, $data);
    }
}
?>
