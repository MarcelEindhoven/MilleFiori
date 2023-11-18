<?php
namespace NieuwenhovenGames\BGA;
/**
 * @see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
 *
 * Player robot bucket notifications subscribes to update storage for these buckets
 * The event is then passed to player robot notifications
 * 
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/FrameworkInterfaces/Notifications.php');
include_once(__DIR__.'/UpdateStorage.php');
include_once(__DIR__.'/EventEmitter.php');

class PlayerRobotBucketNotifications {
    const EVENT_KEY_PUBLIC_MESSAGE = '${player_name} ${field_name} becomes ${field_value}';
    protected ?EventEmitter $event_handler = null;

    static public function create($notifyInterface, $player_robot_data) : PlayerRobotBucketNotifications {
        $handler = new PlayerRobotBucketNotifications();
        return $handler->setNotificationsHandler($notifyInterface)->setPlayerRobotData($player_robot_data);
    }

    public function setNotificationsHandler($notificationsHandler) : PlayerRobotBucketNotifications {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }

    public function setEventEmitter($event_handler) : PlayerRobotBucketNotifications {
        $this->event_handler = $event_handler;
        $this->event_handler->on(UpdateStorage::getBucketSpecificEventName(UpdatePlayerRobotProperties::PLAYER_BUCKET_NAME), [$this, 'playerPropertyUpdated']);
        $this->event_handler->on(UpdateStorage::getBucketSpecificEventName(UpdatePlayerRobotProperties::ROBOT_BUCKET_NAME), [$this, 'robotPropertyUpdated']);
        return $this;
    }

    public function playerPropertyUpdated($event) {
        $this->propertyUpdated($event);
    }

    public function robotPropertyUpdated($event) {
        $this->propertyUpdated($event);
    }

    public function propertyUpdated($event) {
        $player_id = $event[UpdateStorage::EVENT_KEY_NAME_SELECTOR] == 'player_id' ? $event[UpdateStorage::EVENT_KEY_SELECTED] : null;
        $field_name = $event[UpdateStorage::EVENT_KEY_NAME_UPDATED_FIELD];
        $field_value = $event[UpdateStorage::EVENT_KEY_UPDATED_VALUE];
        $notification_arguments = [
            'field_name' => $field_name,
            'field_value' => $field_value,
        ];
        $this->notificationsHandler->notifyAllPlayers(UpdateStorage::EVENT_NAME, PlayerRobotBucketNotifications::EVENT_KEY_PUBLIC_MESSAGE, $notification_arguments, $player_id);
    }
}
?>
