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
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class CurrentCategoriesTest extends TestCase{
    const DEFAULT_SELECTABLE_FIELD_IDS = ['field_ocean_6'];

    protected CurrentCategories $sut;

    protected function setUp(): void {
        $this->player_id = 7;
        $this->sut = CurrentCategories::create([$this->player_id => [Ocean::KEY_PLAYER_POSITION => 5]]);
    }

    public function testSelectableFieldIDs() {
        // Arrange
        $card_type = 5;
        // Act
        $data = $this->sut->getSelectableFieldIDs($this->player_id, $card_type);
        // Assert
        $this->assertEquals(CurrentCategoriesTest::DEFAULT_SELECTABLE_FIELD_IDS, $data);
    }
}
?>
