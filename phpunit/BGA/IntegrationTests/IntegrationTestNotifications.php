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
include_once(__DIR__.'/../../../export/modules/BGA/SubscribedAction.php');
include_once(__DIR__.'/../../../export/modules/BGA/UpdateStorage.php');

class IntegrationTestNotifications extends TestCase{
    protected PlayerRobotNotifications $sut;

    protected function setUp(): void {
        $this->sut = new PlayerRobotNotifications();

        $this->mock_database = $this->createMock(FrameworkInterfaces\Database::class);
        $this->sut_storage = UpdateStorage::create($this->mock_database);

        $this->sut_emitter = new EventEmitter();
        $this->sut_storage->setEventEmitter($this->sut_emitter);
        $this->sut->setEventEmitter($this->sut_emitter);

        $this->mock_notifications = $this->createMock(FrameworkInterfaces\Notifications::class);
        $this->sut->setNotificationsHandler($this->mock_notifications);

        $this->notification_type = 'notification_type';
        $this->notification_log = 'notification_log';
        $this->notification_args = ['argument' => 'notification_args'];

        $this->player_id = 55;
        $this->robot_id = 15;
        $this->input_data = [$this->player_id => ['name'=>'test_name', 'is_player'=> true], $this->robot_id => ['name'=>'robot_name', 'is_player'=> false]];
        $this->sut->setPlayerRobotData($this->input_data);
    }

    public function testpropertyUpdated_Always_notifyAllPlayers() {
        // Arrange
        $this->notification_type = UpdateStorage::EVENT_NAME;
        $this->notification_log = '';

        $additional_arguments = ['player_id' => $this->player_id, 'player_name' => $this->input_data[$this->player_id]['name']];

        $this->bucket_name = 'field';
        $this->field_name_value = 'id';
        $this->value = '3';
        $this->event = [
            UpdateStorage::EVENT_KEY_BUCKET => $this->bucket_name,
            UpdateStorage::EVENT_KEY_NAME_VALUE => $this->field_name_value,
            UpdateStorage::EVENT_KEY_UPDATED_VALUE => $this->value,
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => 'player_id',
            UpdateStorage::EVENT_KEY_SELECTED => $this->player_id
        ];
        $this->notification_args = $this->event;

        $this->mock_notifications->expects($this->exactly(1))->method('notifyAllPlayers')->with($this->notification_type, $this->notification_log, $this->notification_args + $additional_arguments);
        // Act
        $this->sut_emitter->emit(UpdateStorage::EVENT_NAME, $this->event);
        // Assert
    }
}
?>
