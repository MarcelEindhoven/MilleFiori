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

    public function testSelectableFieldIDs() {
        // Arrange
        $card_type = 5;
        // Act
        //$data = $this->sut->getSelectableFieldIDs($this->player_id, $card_type);
        // Assert
        //$this->assertEquals(CurrentCategoriesTest::DEFAULT_SELECTABLE_FIELD_IDS, $data);
    }
}
?>
