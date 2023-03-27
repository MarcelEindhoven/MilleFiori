<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/BGA/NotifyInterface.php');

class NotifyHandler {
    const HAND = 'myhand';

    static public function create($notifyInterface) : NotifyHandler {
        $handler = new NotifyHandler();
        return $handler->setNotifyInterface($notifyInterface);
    }

    public function setNotifyInterface($notifyInterface) : NotifyHandler {
        $this->notifyInterface = $notifyInterface;
        return $this;
    }

    public function notifyPlayerHand($player_id, $hand) {
        if (! $this->isPlayerARobot($player_id)) {
            $this->notifyInterface->notifyPlayer($current_player_id, 'playerHands', 'Pass hand to other player', [NotifyHandler::HAND => $hand]);
        }
    }

    public function isPlayerARobot(int $player_id) : bool {
        return $player_id < 9;
    }

}

?>

