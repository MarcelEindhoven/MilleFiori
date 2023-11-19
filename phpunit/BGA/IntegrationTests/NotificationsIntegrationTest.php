<?php
namespace NieuwenhovenGames\BGA;
/**
 * Check event subscription and unsubscription
 *------
 * MilleFiori implementation unit tests : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 */

include_once(__DIR__.'/../../../vendor/autoload.php');
use PHPUnit\Framework\TestCase;

include_once(__DIR__.'/../../../export/modules/BGA/EventEmitter.php');
include_once(__DIR__.'/../../../export/modules/BGA/FrameworkInterfaces/Notifications.php');
include_once(__DIR__.'/../../../export/modules/BGA/PlayerRobotNotifications.php');
include_once(__DIR__.'/../../../export/modules/BGA/PlayerRobotStorageNotifications.php');
include_once(__DIR__.'/../../../export/modules/BGA/SubscribedAction.php');
include_once(__DIR__.'/../../../export/modules/BGA/UpdateStorage.php');

class NotificationsIntegrationTest extends TestCase{
    protected ?FrameworkInterfaces\Database $mock_database = null;
    protected ?FrameworkInterfaces\Notifications $mock_notifications = null;
    protected ?PlayerRobotStorageNotifications $sut_player_robot_storage_notifications = null;
    protected ?PlayerRobotNotifications $sut_player_robot_notifications = null;
    protected ?EventEmitter $sut_emitter = null;
    protected ?UpdateStorage $sut_storage = null;

    protected function setUp(): void {
        $this->sut_player_robot_notifications = new PlayerRobotNotifications();
        $this->sut_player_robot_storage_notifications = new PlayerRobotStorageNotifications();

        $this->mock_database = $this->createMock(FrameworkInterfaces\Database::class);
        $this->sut_storage = UpdateStorage::create($this->mock_database);

        $this->sut_emitter = new EventEmitter();
        $this->sut_storage->setEventEmitter($this->sut_emitter);
        $this->sut_player_robot_storage_notifications->setEventEmitter($this->sut_emitter);

        $this->mock_notifications = $this->createMock(FrameworkInterfaces\Notifications::class);
        $this->sut_player_robot_notifications->setNotificationsHandler($this->mock_notifications);
        $this->sut_player_robot_storage_notifications->setNotificationsHandler($this->sut_player_robot_notifications);

        $this->notification_type = 'notification_type';
        $this->notification_log = 'notification_log';
        $this->notification_args = ['argument' => 'notification_args'];

        $this->player_id = 55;
        $this->robot_id = 15;
        $this->input_data = [$this->player_id => ['name'=>'test_name', 'is_player'=> true], $this->robot_id => ['name'=>'robot_name', 'is_player'=> false]];
        $this->sut_player_robot_notifications->setPlayerRobotData($this->input_data);
    }

    public function testpropertyUpdated_Always_notifyAllPlayers() {
        // Arrange
        $bucket_name = 'robot';
        $field_name_value = 'score';
        $value = '3';
        $event = [
            UpdateStorage::EVENT_KEY_BUCKET => $bucket_name,
            UpdateStorage::EVENT_KEY_NAME_UPDATED_FIELD => $field_name_value,
            UpdateStorage::EVENT_KEY_UPDATED_VALUE => $value,
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => 'player_id',
            UpdateStorage::EVENT_KEY_SELECTED => $this->player_id
        ];

        $additional_arguments = ['player_id' => $this->player_id, 'player_name' => $this->input_data[$this->player_id]['name'], 'field_name' => $field_name_value, 'field_value' => $value];

        $notification_args = $event + $additional_arguments;

        $this->mock_notifications->expects($this->exactly(1))->method('notifyAllPlayers')->with(UpdateStorage::EVENT_NAME, PlayerRobotStorageNotifications::EVENT_PUBLIC_MESSAGE, $notification_args);
        // Act
        $this->sut_emitter->emit(UpdateStorage::EVENT_NAME, $event);
        // Assert
    }
}
?>
