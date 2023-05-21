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
    const DEFAULT_POSITION_DATA = [3 => [Ocean::KEY_PLAYER_POSITION => 5]];

    protected UpdateOcean $sut;

    protected function setUp(): void {
        $this->player_id = 3;
        $this->position_data = UpdateOceanTest::DEFAULT_POSITION_DATA;
        $this->sut = UpdateOcean::create($this->player_id, $this->position_data);

        $this->mock_event_handler = $this->createMock(\NieuwenhovenGames\BGA\EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_event_handler);
    }

    protected function arrangeForPosition($position) {
        $this->sut = UpdateOcean::create([$this->player_id => [Ocean::KEY_PLAYER_POSITION => $position]]);
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
        $this->chosen_field_id = $this->getFieldIDForPosition(7);
        $event_position = ['player_id' => $this->player_id, 'position' => 7];
        $this->mock_event_handler->expects($this->exactly(2))->method('emit')->withConsecutive(['Position', $event_position], ['SelectExtraCard', []]);
        // Act
        $tooltips = $this->sut->PlayerSelectsField($this->player_id, $this->chosen_field_id);
        // Assert
    }

    public function testUpdate_SelectMax_EmitPositionAndPointsAndExtraCard() {
        // Arrange
        $this->chosen_field_id = $this->getFieldIDForPosition($this->getMaxPosition());
        $event_position = ['player_id' => $this->player_id, 'position' => $this->getMaxPosition()];
        $event_points = ['player_id' => $this->player_id, 'points' => 10];
        $this->mock_event_handler->expects($this->exactly(3))->method('emit')->withConsecutive(['Position', $event_position], ['Points', $event_points], ['SelectExtraCard', []]);
        // Act
        $tooltips = $this->sut->PlayerSelectsField($this->player_id, $this->chosen_field_id);
        // Assert
    }

    private function getMaxPosition() {
        return Ocean::NUMBER_FIELDS - 1;
    }
}
?>
