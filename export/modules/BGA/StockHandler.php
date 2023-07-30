<?php
namespace NieuwenhovenGames\BGA;
/**
 * Send events to JavaScript stock
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class StockHandler {
    const EVENT_NEW_STOCK_CONTENT = 'newStockContent';
    const ARGUMENT_KEY_STOCK = 'stock_id';
    const ARGUMENT_KEY_STOCK_ITEMS = 'items';

    static public function create($notificationsHandler) {
        $object = new StockHandler();
        return $object->setNotificationsHandler($notificationsHandler);
    }

    public function setNotificationsHandler($notificationsHandler) : StockHandler {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }
    public function setNewStockContent(string $player_id, string $stock_id, array $items, string $message) {
        $arguments = [StockHandler::ARGUMENT_KEY_STOCK => $stock_id, StockHandler::ARGUMENT_KEY_STOCK_ITEMS => $items];
        $this->notificationsHandler->notifyPlayer($player_id, StockHandler::EVENT_NEW_STOCK_CONTENT, $message, $arguments);
    }
}
?>
