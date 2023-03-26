<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/BGA/CardsInterface.php');

class CardsHandler {
    static public function create($cards) : CardsHandler {
        $cards = new CardsHandler();
        return $cards->setCards($cards);
    }

    public function setCards($cards) : CardsHandler {
        $this->cards = $cards;
        return $this;
    }

    public function setNotifyInterface($notifyInterface) : CardsHandler {
        $this->notifyInterface = $notifyInterface;
        return $this;
    }

}

?>

