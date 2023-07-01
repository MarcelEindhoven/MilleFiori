<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../BGA/NotifyInterface.php');

class NotifyHandler {
    static public function create($notifyInterface) : NotifyHandler {
        $handler = new NotifyHandler();
        return $handler->setNotifyInterface($notifyInterface);
    }

    public function setNotifyInterface($notifyInterface) : NotifyHandler {
        $this->notifyInterface = $notifyInterface;
        return $this;
    }

    public function notifyPlayerIfNotRobot($player_id, string $notification_type, string $notification_log, array $notification_args) : void {
        if ($this->isPlayerARobot($player_id)) {
            return;
        }
        $this->notifyInterface->notifyPlayer($player_id, $notification_type, $notification_log, $notification_args);
    }

    public function notifyPlayerHand($player_id, $hand, $message) {
        $this->notifyPlayerIfNotRobot($player_id, 'newPlayerHand', $message, [\NieuwenhovenGames\BGA\Deck::PLAYER_HAND => $hand]);
    }

    public function notifyEmptyPlayedHand() {
        $this->notifyInterface->notifyAllPlayers('emptyPlayedHand', '', []);
    }

    public function notifyCardMoved($card, $message, $from_stock, $to_stock) {
        $content = ['card' => $card];
        if ($from_stock) {
            $content['fromStock'] = $from_stock;
        }
        if ($to_stock) {
            $content['toStock'] = $to_stock;
        }
        $this->notifyInterface->notifyAllPlayers('cardMoved', $message, $content);
    }

    public function notifyCardMovedFromPrivateToPublic($card, $message, $player_id, $from_stock, $to_stock) {
        $content = ['card' => $card];
        if (! $this->isPlayerARobot($player_id)) {
            $content['player_id'] = $player_id;
        }
        
        if ($from_stock) {
            $content['fromStock'] = $from_stock;
        }
        if ($to_stock) {
            $content['toStock'] = $to_stock;
        }
        $this->notifyInterface->notifyAllPlayers('cardMoved', $message, $content);
    }

    public function isPlayerARobot(int $player_id) : bool {
        return $player_id < 9;
    }

}

?>

