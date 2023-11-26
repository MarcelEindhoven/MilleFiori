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

include_once(__DIR__.'/EventEmitter.php');
include_once(__DIR__.'/PlayerRobotNotifications.php');
include_once(__DIR__.'/UpdateStorage.php');

class PlayerRobotStorageNotifications {
    const EVENT_PUBLIC_MESSAGE = '${player_name} ${field_name_value} becomes ${field_value}';
    protected ?EventEmitter $event_handler = null;
    protected ?PlayerRobotNotifications $notificationsHandler = null;

    static public function create($notifyInterface) : PlayerRobotStorageNotifications {
        $handler = new PlayerRobotStorageNotifications();
        return $handler->setNotificationsHandler($notifyInterface);
    }

    public function setNotificationsHandler($notificationsHandler) : PlayerRobotStorageNotifications {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }

    public function setEventEmitter($event_handler) : PlayerRobotStorageNotifications {
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
        $this->notificationsHandler->notifyAllPlayers(UpdateStorage::EVENT_NAME, PlayerRobotStorageNotifications::EVENT_PUBLIC_MESSAGE, $event, $player_id);
    }
}
?>
