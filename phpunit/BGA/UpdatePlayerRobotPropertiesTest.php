<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/UpdatePlayerRobotProperties.php');
include_once(__DIR__.'/../../export/modules/BGA/EventEmitter.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');

class UpdatePlayerRobotPropertiesTest extends TestCase{
    const DEFAULT_ROBOT_ID = 3;
    const DEFAULT_PLAYER_ID = 33;
    const DEFAULT_VALUE = 5;
    const DEFAULT_KEY = 'key';
    const DEFAULT_DATA = [
        UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID => [UpdatePlayerRobotPropertiesTest::DEFAULT_KEY => UpdatePlayerRobotPropertiesTest::DEFAULT_VALUE],
        UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID => [UpdatePlayerRobotPropertiesTest::DEFAULT_KEY => UpdatePlayerRobotPropertiesTest::DEFAULT_VALUE]];

    protected UpdatePlayerRobotProperties $sut;

    protected function setUp(): void {
        $this->sut = new UpdatePlayerRobotProperties(UpdatePlayerRobotPropertiesTest::DEFAULT_DATA);

        $this->mock_emitter = $this->createMock(EventEmitter::class);
        $this->sut->setEventEmitter($this->mock_emitter);
    }

    public function testGet_InitialValue_DefaultReturned() {
        // Arrange
        // Act
        $value = $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY];
        // Assert
        $this->assertEquals(UpdatePlayerRobotPropertiesTest::DEFAULT_VALUE, $value);
    }

    public function testSet_NewValue_NewReturned() {
        // Arrange
        $new_value = 9;
        // Act
        $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY] = $new_value;
        // Assert
        $value = $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY];
        $this->assertEquals($new_value, $value);
    }

    public function testSet_NewRobotValue_EmitRobotBucketUpdated() {
        // Arrange
        $new_value = 9;
        $this->arrangeSet(UpdatePlayerRobotProperties::EVENT_KEY_BUCKET_ROBOT, UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID, $new_value);
        // Act
        $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY] = $new_value;
        // Assert
    }

    public function testSet_NewPlayerValue_EmitPlayerBucketUpdated() {
        // Arrange
        $new_value = 9;
        $this->arrangeSet(UpdatePlayerRobotProperties::EVENT_KEY_BUCKET_PLAYER, UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID, $new_value);
        // Act
        $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY] = $new_value;
        // Assert
    }

    private function arrangeSet($bucket_name, $player_id, $new_value)
    {
        $this->event = [
            UpdateStorage::EVENT_KEY_BUCKET => $bucket_name,
            UpdateStorage::EVENT_KEY_NAME_VALUE => UpdatePlayerRobotPropertiesTest::DEFAULT_KEY,
            UpdateStorage::EVENT_KEY_UPDATED_VALUE => $new_value,
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => UpdatePlayerRobotProperties::EVENT_KEY_NAME_SELECTOR,
            UpdateStorage::EVENT_KEY_SELECTED => $player_id
        ];
        $this->mock_emitter->expects($this->exactly(1))->method('emit')->with(UpdateStorage::EVENT_NAME, $this->event);
    }
}
?>

