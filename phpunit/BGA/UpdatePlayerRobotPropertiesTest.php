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
    const DEFAULT_NAME = 'player_name';
    const DEFAULT_DATA = [
        UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID => [
            UpdatePlayerRobotProperties::KEY_ID => UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID,
            UpdatePlayerRobotPropertiesTest::DEFAULT_KEY => UpdatePlayerRobotPropertiesTest::DEFAULT_VALUE,
            UpdatePlayerRobotProperties::KEY_NAME => UpdatePlayerRobotPropertiesTest::DEFAULT_NAME
        ],
        UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID => [
            UpdatePlayerRobotProperties::KEY_ID => UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID,
            UpdatePlayerRobotPropertiesTest::DEFAULT_KEY => UpdatePlayerRobotPropertiesTest::DEFAULT_VALUE,
            UpdatePlayerRobotProperties::KEY_NAME => UpdatePlayerRobotPropertiesTest::DEFAULT_NAME
        ]
    ];

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
        $this->arrangeSet(UpdatePlayerRobotProperties::ROBOT_BUCKET_NAME, UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID, $new_value);
        // Act
        $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_ROBOT_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY] = $new_value;
        // Assert
    }

    public function testSet_NewPlayerValue_EmitPlayerBucketUpdated() {
        // Arrange
        $new_value = 9;
        $this->arrangeSet(UpdatePlayerRobotProperties::PLAYER_BUCKET_NAME, UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID, $new_value);
        // Act
        $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY] = $new_value;
        // Assert
    }

    private function arrangeSet($bucket_name, $player_id, $new_value, $public_message = null)
    {
        // see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
        $this->event = [
            // Event info for updating the database
            UpdateStorage::EVENT_KEY_BUCKET => $bucket_name,
            UpdateStorage::EVENT_KEY_NAME_UPDATED_FIELD => UpdatePlayerRobotProperties::PLAYER_KEY_PREFIX . UpdatePlayerRobotPropertiesTest::DEFAULT_KEY,
            UpdateStorage::EVENT_KEY_UPDATED_VALUE => $new_value,
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => UpdatePlayerRobotProperties::PLAYER_KEY_PREFIX . UpdatePlayerRobotProperties::KEY_ID,
            UpdateStorage::EVENT_KEY_SELECTED => $player_id,
            // Event info to inform the players
            UpdatePlayerRobotProperties::EVENT_KEY_NAME => UpdatePlayerRobotPropertiesTest::DEFAULT_NAME
        ];
        if ($public_message) {
            $this->event[PlayerRobotNotifications::EVENT_KEY_PUBLIC_MESSAGE] = $public_message;
        }
        $this->mock_emitter->expects($this->exactly(1))->method('emit')->with(UpdateStorage::EVENT_NAME, $this->event);
    }

    public function testPublicMessage_Present_MessageInEvent() {
        // Arrange
        $new_value = 9;
        $public_message = 'hello world';
        $this->sut->setPublicMessageWhenUpdated(UpdatePlayerRobotPropertiesTest::DEFAULT_KEY, $public_message);
        $this->arrangeSet(UpdatePlayerRobotProperties::PLAYER_BUCKET_NAME, UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID, $new_value, $public_message);
        // Act
        $this->sut[UpdatePlayerRobotPropertiesTest::DEFAULT_PLAYER_ID][UpdatePlayerRobotPropertiesTest::DEFAULT_KEY] = $new_value;
        // Assert
    }
}
?>
