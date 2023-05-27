<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/ActionsAndStates/UpdateOcean.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');

class UpdateOceanTest extends TestCase{
    const DEFAULT_SELECTABLE_FIELD_IDS = ['field_ocean_8'];
    const DEFAULT_POSITION_DATA = [3 => 5];

    protected UpdateOcean $sut;

    protected function setUp(): void {
        $this->player_id = 3;
        $this->position_data = UpdateOceanTest::DEFAULT_POSITION_DATA;
        $this->sut = UpdateOcean::create($this->position_data);

        $this->mock_event_handler = $this->createMock(\NieuwenhovenGames\BGA\EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_event_handler);

        $this->mock_array = $this->createMock(\ArrayAccess::class);
    }

    protected function arrangeForInitialPosition($position) {
        $this->mock_array->expects($this->exactly(1))->method('offsetGet')->withConsecutive([$this->player_id])->will($this->returnValue($position));
        $this->sut->setOceanPositions($this->mock_array);
    }

    protected function arrangeForNewPosition($position) {
        $this->mock_array->expects($this->exactly(1))->method('offsetSet')->with($this->player_id, $position);
        $this->chosen_field_id = $this->getFieldIDForPosition($position);
    }

    protected function getFieldIDForPosition($position): string {
        return 'field_ocean_'. $position;
    }

    // Rewards

    // Update
    public function testUpdate_NoMovement_EmitNothing() {
        // Arrange
        $this->chosen_field_id = $this->getFieldIDForPosition(5);
        $this->mock_event_handler->expects($this->exactly(0))->method('emit');
        // Act
        $tooltips = $this->sut->PlayerSelectsField($this->player_id, $this->chosen_field_id);
        // Assert
    }

    public function testUpdate_Select7_EmitPositionAndExtraCard() {
        // Arrange
        $this->arrangeForInitialPosition(5);
        $this->arrangeForNewPosition(7);
        $this->mock_event_handler->expects($this->exactly(1))->method('emit')->withConsecutive(['SelectExtraCard', []]);
        // Act
        $tooltips = $this->sut->PlayerSelectsField($this->player_id, $this->chosen_field_id);
        // Assert
    }

    public function testUpdate_SelectMax_EmitPositionAndPointsAndExtraCard() {
        // Arrange
        $this->arrangeForInitialPosition(11);
        $this->arrangeForNewPosition($this->getMaxPosition());
        $event_points = ['player_id' => $this->player_id, 'points' => 10];
        $this->mock_event_handler->expects($this->exactly(2))->method('emit')->withConsecutive(['Points', $event_points], ['SelectExtraCard', []]);
        // Act
        $tooltips = $this->sut->PlayerSelectsField($this->player_id, $this->chosen_field_id);
        // Assert
    }

    private function getMaxPosition() {
        return Ocean::NUMBER_FIELDS - 1;
    }
}
?>
