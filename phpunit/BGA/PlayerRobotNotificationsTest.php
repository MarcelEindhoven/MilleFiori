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

include_once(__DIR__.'/../../export/modules/BGA/PlayerRobotNotifications.php');
include_once(__DIR__.'/../../export/modules/BGA/UpdateStorage.php');

include_once(__DIR__.'/../../export/modules/BGA/FrameworkInterfaces/Notifications.php');

class TestPlayerRobotNotifications extends PlayerRobotNotifications {
}

class PlayerRobotNotificationsTest extends TestCase{
    protected PlayerRobotNotifications $sut;
    protected ?FrameworkInterfaces\Notifications $mock_notifications = null;
    protected ?EventEmitter $mock_emitter = null;

    protected function setUp(): void {
        $this->sut = new PlayerRobotNotifications();

        $this->mock_notifications = $this->createMock(FrameworkInterfaces\Notifications::class);
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

        $this->notification_type = 'notification_type';
        $this->notification_log = 'notification_log';
        $this->notification_args = ['argument' => 'notification_args'];

        $this->player_id = 55;
        $this->robot_id = 15;
        $this->input_data = [$this->player_id => ['name'=>'test_name', 'is_player'=> true], $this->robot_id => ['name'=>'robot_name', 'is_player'=> false]];
        $this->sut->setPlayerRobotData($this->input_data);
    }

    public function testnotifyAllPlayers_Always_PassArguments() {
        // Arrange
        $this->mock_notifications->expects($this->exactly(1))->method('notifyAllPlayers')->with($this->notification_type, $this->notification_log, $this->notification_args);
        // Act
        $this->sut->notifyAllPlayers($this->notification_type, $this->notification_log, $this->notification_args);
        // Assert
    }

    public function testnotifyAllPlayers_AdditionalID_AdditionalArguments() {
        // Arrange
        $additional_arguments = ['player_id' => $this->robot_id, 'player_name' => $this->input_data[$this->robot_id]['name']];
        $this->mock_notifications->expects($this->exactly(1))->method('notifyAllPlayers')
        ->with($this->notification_type, $this->notification_log, $this->notification_args + $additional_arguments);
        // Act
        $this->sut->notifyAllPlayers($this->notification_type, $this->notification_log, $this->notification_args, $this->robot_id);
        // Assert
    }

    public function testnotifyPlayer_Player_AdditionalArguments() {
        // Arrange
        $additional_arguments = ['player_id' => $this->player_id, 'player_name' => $this->input_data[$this->player_id]['name']];
        $this->mock_notifications->expects($this->exactly(1))->method('notifyPlayer')
        ->with($this->player_id, $this->notification_type, $this->notification_log, $this->notification_args + $additional_arguments);
        // Act
        $this->sut->notifyPlayer($this->player_id, $this->notification_type, $this->notification_log, $this->notification_args);
        // Assert
    }

    public function testnotifyPlayer_Robot_NothingHappens() {
        // Arrange
        $this->mock_notifications->expects($this->exactly(0))->method('notifyPlayer');
        // Act
        $this->sut->notifyPlayer($this->robot_id, $this->notification_type, $this->notification_log, $this->notification_args);
        // Assert
    }

    public function testpropertyUpdated_PublicMessage_Notify() {
        // Arrange
        $this->notification_type = UpdateStorage::EVENT_NAME;
        $this->notification_log = 'Message';

        $additional_arguments = ['player_id' => $this->player_id, 'player_name' => $this->input_data[$this->player_id]['name']];
        $event = [
            PlayerRobotNotifications::EVENT_KEY_PUBLIC_MESSAGE => $this->notification_log,
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => 'player_id',
            UpdateStorage::EVENT_KEY_SELECTED => $this->player_id];
        $this->notification_args = $event;

        $this->mock_notifications->expects($this->exactly(1))->method('notifyAllPlayers')->with($this->notification_type, $this->notification_log, $this->notification_args + $additional_arguments);
        // Act
        $this->sut->propertyUpdated($event);
        // Assert
    }

    public function testpropertyUpdated_PublicMessage_NotifyWithoutMessage() {
        // Arrange
        $this->notification_type = UpdateStorage::EVENT_NAME;
        $this->notification_log = '';

        $additional_arguments = ['player_id' => $this->player_id, 'player_name' => $this->input_data[$this->player_id]['name']];
        $event = [
            UpdateStorage::EVENT_KEY_NAME_SELECTOR => 'player_id',
            UpdateStorage::EVENT_KEY_SELECTED => $this->player_id];
        $this->notification_args = $event;

        $this->mock_notifications->expects($this->exactly(1))->method('notifyAllPlayers')->with($this->notification_type, $this->notification_log, $this->notification_args + $additional_arguments);
        // Act
        $this->sut->propertyUpdated($event);
        // Assert
    }
}
?>
