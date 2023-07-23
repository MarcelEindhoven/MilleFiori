<?php
namespace NieuwenhovenGames\BGA;
/**
 * @see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
 *
 * Notifications are sent at the very end of the user request, when it ends normally.
 * It means that if you throw an exception for any reason (ex: move not allowed), no notifications will be sent to players.
 * Notifications sent between the game start (setupNewGame) and the end of the "action" method of the first active state will never reach their destination.
 * 
 * Only notify a player if it has a player board, so if it is not a robot
 * Automatically fill in the player id and name in the arguments
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class PlayerRobotNotifications {
    public function setPlayerRobotData($data) : PlayerRobotNotifications {
        $this->data = $data;
        return $this;
    }

    public function setNotificationsHandler($notificationsHandler) : PlayerRobotNotifications {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }

    public function notifyPlayer($player_id, string $notification_type, string $notification_log, array $notification_args) : void {
        if ($this->data[$player_id]['is_player']) {
            $this->notificationsHandler->notifyPlayer($player_id, $notification_type, $notification_log, $notification_args+ $this->additionalArguments($player_id));    
        }
    }

    public function notifyAllPlayers(string $notification_type, string $notification_log, array $notification_args, $player_id = null) : void {
        $this->notificationsHandler->notifyAllPlayers($notification_type, $notification_log, $notification_args + $this->additionalArguments($player_id));
    }

    protected function additionalArguments($player_id) {
        return $player_id ? ['player_id' => $player_id, 'player_name' => $this->data[$player_id]['name']] : [];
    }
}
?>
