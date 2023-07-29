<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class StockHandler {

    public function setNotificationsHandler($notificationsHandler) : StockHandler {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }
}
?>
