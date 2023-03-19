<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

interface NotifyInterface {
    public function notifyPlayer($player_id, string $notification_type, string $notification_log, array $notification_args) : void;
    public function notifyAllPlayers(string $notification_type, string $notification_log, array $notification_args) : void;
}
?>
