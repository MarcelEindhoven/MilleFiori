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
include_once(__DIR__.'/BGA/NotifyInterface.php');

class CardsHandler {
    const LOCATION_SWAP = -3;
    const HAND = 'hand';
    const PLAYED_HAND = 'playedhand';
    const SIDEBOARD_HAND = 'boardhand';

    static public function create($cards) : CardsHandler {
        $cardsHandler = new CardsHandler();
        return $cardsHandler->setCards($cards);
    }

    public function setCards($cards) : CardsHandler {
        $this->cards = $cards;
        return $this;
    }

    public function setNotifyHandler($notifyHandler) : CardsHandler {
        $this->notifyHandler = $notifyHandler;
        return $this;
    }

    public function swapHands(array $player_ids) : CardsHandler {
        if (count($player_ids) < 2) {
            return $this;
        }
        $previous_player = CardsHandler::LOCATION_SWAP;
        foreach ($player_ids as $player_id) {
            $this->cards->moveAllCardsInLocation(CardsHandler::HAND, CardsHandler::HAND, $player_id, $previous_player);
            $previous_player = $player_id;
        }

        $this->cards->moveAllCardsInLocation(CardsHandler::HAND, CardsHandler::HAND, CardsHandler::LOCATION_SWAP, $previous_player);
        foreach ($player_ids as $player_id) {
            $this->notifyHandler->notifyPlayerHand($player_id, $this->cards->getCardsInLocation(CardsHandler::HAND, $player_id), 'Pass hand to other player');
        }

        return $this;
    }

    public function selectExtraCard($card_id) {
        $this->cards->moveCard($card_id, CardsHandler::PLAYED_HAND);
        $this->notifyHandler->notifyCardMoved($this->cards->getCard($card_id), 'Playing extra card', CardsHandler::SIDEBOARD_HAND, CardsHandler::PLAYED_HAND);
    }

}

?>

