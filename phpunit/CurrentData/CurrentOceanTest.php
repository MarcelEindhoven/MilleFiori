<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/CurrentData/CurrentOcean.php');
include_once(__DIR__.'/../../export/modules/BGA/Storage.php');

class CurrentOceanTest extends TestCase{
    const DEFAULT_SELECTABLE_FIELD_IDS = ['field_ocean_8'];
    const DEFAULT_POSITION_DATA = [3 => [Ocean::KEY_PLAYER_POSITION => 5]];

    protected CurrentOcean $sut;

    protected function setUp(): void {
        $this->player_id = 3;
        $this->position_data = CurrentOceanTest::DEFAULT_POSITION_DATA;
        $this->sut = CurrentOcean::create($this->position_data);
    }

    protected function arrangeForPosition($position) {
        $this->sut = CurrentOcean::create([$this->player_id => [Ocean::KEY_PLAYER_POSITION => $position]]);
    }
    protected function actSelectableFieldIDs($card_id) {
        $this->data = $this->sut->getSelectableFieldIDs($this->player_id, $card_id);
    }

    public function testSelectableFieldIDs_Position0_Field3() {
        // Arrange
        $this->arrangeForPosition(0);
        // Act
        $this->actSelectableFieldIDs(7);
        // Assert
        $this->assertFieldID(3);
    }

    public function testSelectableFieldIDs_Position5_Field8() {
        // Arrange
        $this->arrangeForPosition(5);
        // Act
        $this->actSelectableFieldIDs(7);
        // Assert
        $this->assertFieldID(8);
    }

    public function testSelectableFieldIDs_PositionMax_FieldMax() {
        // Arrange
        $this->arrangeForPosition(20);
        // Act
        $this->actSelectableFieldIDs(7);
        // Assert
        $this->assertFieldID(20);
    }

    public function testTooltips_Get_Array() {
        // Arrange
        // Act
        $tooltips = $this->sut->getTooltipsCards();
        // Assert
        $this->assertCount(count(Ocean::PLACES_PER_CARD), $tooltips);
    }

    protected function getFieldIDForPosition($position): string {
        return 'field_ocean_'. $position;
    }
    protected function assertFieldID($expected_position) {
        $this->assertEquals([$this->getFieldIDForPosition($expected_position)], $this->data);
    }
}
?>
