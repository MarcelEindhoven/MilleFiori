<?php
namespace NieuwenhovenGames\BGA\FrameworkInterfaces;
/**
 * @see https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php
 *
 * Notifications are sent at the very end of the user request, when it ends normally.
 * It means that if you throw an exception for any reason (ex: move not allowed), no notifications will be sent to players.
 * Notifications sent between the game start (setupNewGame) and the end of the "action" method of the first active state will never reach their destination.
 * 
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

interface Notifications {
    public function notifyPlayer($player_id, string $notification_type, string $notification_log, array $notification_args) : void;
    public function notifyAllPlayers(string $notification_type, string $notification_log, array $notification_args) : void;
    public function clienttranslate(string $notification_log) : string;
}
?>
