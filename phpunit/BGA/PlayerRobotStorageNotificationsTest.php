<?php
namespace NieuwenhovenGames\BGA;
/**
 * Check event subscription and unsubscription
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotStorageNotifications.php');
include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');

class PlayerRobotStorageNotificationsTest extends TestCase{
    protected PlayerRobotStorageNotifications $sut;
    protected ?PlayerRobotNotifications $mock_notifications = null;
    protected ?EventEmitter $mock_emitter = null;

    protected function setUp(): void {
        $this->sut = new PlayerRobotStorageNotifications();

        $this->mock_notifications = $this->createMock(PlayerRobotNotifications::class);
        $this->sut->setNotificationsHandler($this->mock_notifications);

        $this->mock_emitter = $this->createMock(EventEmitter::class);
        $this->mock_emitter->expects($this->exactly(2))->method('on');
        /*
        $this->mock_emitter->expects($this->exactly(2))->method('on')->withConsecutive(
            [UpdateStorage::getBucketSpecificEventName(UpdatePlayerRobotProperties::PLAYER_BUCKET_NAME), [$this->mock_emitter, 'playerPropertyUpdated']],
            [UpdateStorage::getBucketSpecificEventName(UpdatePlayerRobotProperties::ROBOT_BUCKET_NAME), [$this->mock_emitter, 'robotPropertyUpdated']],
        );
        */
        $this->sut->setEventEmitter($this->mock_emitter);

        $this->player_id = 55;
        $this->robot_id = 15;
    }

    public function testpropertyUpdated_PublicMessage_Notify() {
        // Arrange
        $notification_type = UpdateStorage::EVENT_NAME;
        $notification_log = '${player_name} ${field_name} becomes ${field_value}';
        $field_name = 'field_test ';
        $field_value = 'value_test ';

        $event = [
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => 'player_id',
            UpdateStorage::EVENT_KEY_SELECTED => $this->player_id,
            UpdateStorage::EVENT_KEY_NAME_UPDATED_FIELD => $field_name,
            UpdateStorage::EVENT_KEY_UPDATED_VALUE => $field_value,
        ];
        $notification_args = [
            'field_name' => $field_name,
            'field_value' => $field_value,
            ];

        $this->mock_notifications->expects($this->exactly(1))->method('notifyAllPlayers')->with($notification_type, $notification_log, $notification_args, $this->player_id);
        // Act
        $this->sut->propertyUpdated($event);
        // Assert
    }
}
?>
