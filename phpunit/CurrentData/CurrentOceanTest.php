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

class CurrentOceanTest extends TestCase{
    const DEFAULT_SELECTABLE_FIELD_IDS = ['field_ocean_8'];
    const DEFAULT_POSITION_DATA = [3 => [Ocean::KEY_PLAYER_POSITION => 5]];

    protected CurrentOcean $sut;

    protected function setUp(): void {
        $this->player_id = 3;
        $this->position_data = CurrentOceanTest::DEFAULT_POSITION_DATA;
        $this->sut = CurrentOcean::create($this->position_data);
    }

    // getSelectableFieldIDs
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
        $this->actSelectableFieldIDs(card_id: 7);
        // Assert
        $this->assertFieldID(3);
    }

    public function testSelectableFieldIDs_Position5_Field8() {
        // Arrange
        $this->arrangeForPosition(5);
        // Act
        $this->actSelectableFieldIDs(card_id: 7);
        // Assert
        $this->assertFieldID(8);
    }

    public function testSelectableFieldIDs_PositionMax_FieldMax() {
        // Arrange
        $this->arrangeForPosition(20);
        // Act
        $this->actSelectableFieldIDs(card_id: 7);
        // Assert
        $this->assertFieldID(20);
    }

    protected function getFieldIDForPosition($position): string {
        return 'field_ocean_'. $position;
    }
    protected function assertFieldID($expected_position) {
        $this->assertEquals([$this->getFieldIDForPosition($expected_position)], $this->data);
    }

    // Rewards

    public function testReward_Zero_NoReward() {
        // Arrange
        $this->arrangeForPosition(0);
        // Act
        $reward = $this->actReward(new_position: 0);
        // Assert
        $this->assertNoReward($reward);
    }

    public function testReward_One_OnePoint() {
        // Arrange
        $this->arrangeForPosition(0);
        // Act
        $reward = $this->actReward(new_position: 1);
        // Assert
        $this->assertPoints($reward, 1);
    }

    public function testReward_OneTooMuch_Exception() {
        // Arrange
        $this->arrangeForPosition(0);
        $this->expectWarning();
        // Act
        $reward = $this->actReward(new_position: Ocean::NUMBER_FIELDS);
        // Assert
    }

    public function testReward_MaximumID_Points() {
        // Arrange
        $this->arrangeForPosition(0);
        // Act
        $reward = $this->actReward(new_position: $this->getMaxPosition());
        // Assert
        $this->assertPoints($reward, 10);
    }

    public function testReward_MaximumIDNoMove_NoReward() {
        // Arrange
        $this->arrangeForPosition($this->getMaxPosition());
        // Act
        $reward = $this->actReward(new_position: $this->getMaxPosition());
        // Assert
        $this->assertNoReward($reward);
    }

    private function actReward($new_position) {
        return $this->sut->getReward($this->player_id, $this->getFieldIDForPosition($new_position));
    }

    private function assertNoReward($reward) {
        $this->assertPoints($reward, 0);
        $this->assertFalse($reward['extra_card']);
    }

    private function assertPoints($reward, $expected_points) {
        $this->assertEquals($expected_points, $reward['points']);
    }

    // Tooltips
    public function testTooltips_Get_Array() {
        // Arrange
        // Act
        $tooltips = $this->sut->getTooltipsCards();
        // Assert
        $this->assertCount(count(Ocean::PLACES_PER_CARD), $tooltips);
    }

    private function getMaxPosition() {
        return Ocean::NUMBER_FIELDS - 1;
    }
}
?>
