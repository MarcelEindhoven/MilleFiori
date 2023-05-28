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

class UpdatePlayerRobotPropertiesTest extends TestCase{
    const DEFAULT_PLAYER_ID = 3;
    const DEFAULT_POSITION = 5;
    const DEFAULT_KEY = 'key';
    const DEFAULT_DATA = [UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID => [UpdatePlayerRobotPropertiesTest::DEFAULT_KEY => UpdatePlayerRobotPropertiesTest::DEFAULT_POSITION]];

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
        $this->assertEquals(UpdatePlayerRobotPropertiesTest::DEFAULT_POSITION, $value);
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

    public function testSet_NewValue_EmitBucketUpdated() {
        // Arrange
        $new_value = 9;
        $this->mock_emitter->expects($this->exactly(1))->method('emit');
        // Act
        $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY] = $new_value;
        // Assert
    }
}
?>
