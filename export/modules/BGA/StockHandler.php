<?php
namespace NieuwenhovenGames\BGA;
/**
 * Send events to JavaScript stock
 * Handle public versus private stocks
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */
include_once(__DIR__.'/FrameworkInterfaces/Notifications.php');

class StockHandler {
    const EVENT_NEW_STOCK_CONTENT = 'newStockContent';
    const EVENT_STOCK_TO_STOCK = 'stockToStock';
    const EVENT_PLAYER_TO_STOCK = 'playerToStock';

    const ARGUMENT_KEY_STOCK = 'stock_id';
    const ARGUMENT_KEY_STOCK_FROM = 'from';
    const ARGUMENT_KEY_STOCK_TO = 'to';

    const ARGUMENT_KEY_STOCK_ITEMS = 'items';
    const ARGUMENT_KEY_STOCK_ITEM = 'item';

    static public function create($notificationsHandler) {
        $object = new StockHandler();
        return $object->setNotificationsHandler($notificationsHandler);
    }

    public function setNotificationsHandler($notificationsHandler) : StockHandler {
        $this->notificationsHandler = $notificationsHandler;
        return $this;
    }

    public function setNewStockContent($player_id, string $stock_id, array $items, string $message) {
        $arguments = [StockHandler::ARGUMENT_KEY_STOCK => $stock_id, StockHandler::ARGUMENT_KEY_STOCK_ITEMS => $items];
        $this->notificationsHandler->notifyPlayer($player_id, StockHandler::EVENT_NEW_STOCK_CONTENT, $message, $arguments);
    }

    public function moveCardPrivate($player_id, string $from, string $to, array $item, string $message) {
        $arguments = $this->getArgumentsMove($from, $to, $item);
        $this->notificationsHandler->notifyPlayer($player_id, StockHandler::EVENT_STOCK_TO_STOCK, $message, $arguments);
    }

    public function moveCardPrivatePublic($player_id, string $from, string $to, array $item, string $message_private, string $message_public) {
        $this->moveCardPrivate($player_id, $from, $to, $item, $message_private);
        $arguments = $this->getArgumentsMove($from, $to, $item);
        $this->notificationsHandler->notifyAllPlayers(StockHandler::EVENT_PLAYER_TO_STOCK, $message_public, $arguments, $player_id);
    }

    public function moveCardPublic(string $from, string $to, array $item, string $message_public = '') {
        $arguments = $this->getArgumentsMove($from, $to, $item);
        $this->notificationsHandler->notifyAllPlayers(StockHandler::EVENT_STOCK_TO_STOCK, $message_public, $arguments);
    }

    protected function getArgumentsMove(string $from, string $to, array $item) {
        return  [StockHandler::ARGUMENT_KEY_STOCK_FROM => $from, StockHandler::ARGUMENT_KEY_STOCK_TO => $to, StockHandler::ARGUMENT_KEY_STOCK_ITEM => $item];
    }
}
?>
