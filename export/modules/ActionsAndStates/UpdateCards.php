<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Update card database
 * Notify of database changes
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/CardsInterface.php');
include_once(__DIR__.'/../BGA/NotifyInterface.php');
include_once(__DIR__.'/../CardsHandler.php');

class UpdateCards extends CardsHandler {
    const DECK = 'deck';
    const LOCATION_SWAP = -3;
    const HAND = 'hand';
    const SELECTED_HAND = 'selectedhand';
    const PLAYED_HAND = 'playedhand';
    const SIDEBOARD = 'sideboard';
    const DISCARD_PILE = 'discard';

    static public function create($cards) : UpdateCards {
        $cardsHandler = new UpdateCards();
        return $cardsHandler->setCards($cards);
    }

    public function setCards($cards) : UpdateCards {
        $this->cards = $cards;
        return $this;
    }

    public function setNotifyHandler($notifyHandler) : UpdateCards {
        $this->notifyHandler = $notifyHandler;
        return $this;
    }

    public function setPlayerIDs(array $player_ids) : UpdateCards {
        $this->player_ids = $player_ids;
        return $this;
    }

    public function haveAllPlayersSameHandCount() : bool {
        // Do all players have the same number of cards (> 0) in their hand?
        return 1 == count(array_unique(array_values($this->cards->countCardsByLocationArgs(CardsHandler::HAND))));
    }

    public function areAnyCardsSelected() : bool {
        return (bool) $this->cards->countCardsByLocationArgs(CardsHandler::SELECTED_HAND);
    }

    public function swapHands() : UpdateCards {
        if (count($this->player_ids) < 2) {
            return $this;
        }
        $previous_player = UpdateCards::LOCATION_SWAP;
        foreach ($this->player_ids as $player_id) {
            $this->cards->moveAllCardsInLocation(CardsHandler::HAND, CardsHandler::HAND, $player_id, $previous_player);
            $previous_player = $player_id;
        }

        $this->cards->moveAllCardsInLocation(CardsHandler::HAND, CardsHandler::HAND, UpdateCards::LOCATION_SWAP, $previous_player);
        foreach ($this->player_ids as $player_id) {
            $this->notifyHandler->notifyPlayerHand($player_id, $this->cards->getCardsInLocation(CardsHandler::HAND, $player_id), 'Pass hand to other player');
        }

        return $this;
    }

    public function dealNewHands($number_cards) : UpdateCards {
        foreach ($this->player_ids as $player_id) {
            $this->cards->pickCards($number_cards, 'deck', $player_id);
            $this->notifyHandler->notifyPlayerHand($player_id, $this->cards->getCardsInLocation(CardsHandler::HAND, $player_id), 'Deal new hand');
            }

        return $this;
    }

    public function moveHandsToSideboard() : UpdateCards {
        foreach ($this->player_ids as $player_id) {
            $this->movePrivateToPublic('Giving up own card', $player_id, CardsHandler::HAND, CardsHandler::SIDEBOARD);
        }

        return $this;
    }

    public function moveFromHandToSelected($card_id, $current_player_id) {
        foreach ($this->cards->getCardsInLocation('selectedhand', $current_player_id) as $selectedCard) {
            $this->notifyHandler->notifyPlayerIfNotRobot($current_player_id, 'cardMoved', '', ['fromStock' => 'selectedhand', 'toStock' => 'hand', 'card' => $selectedCard]);
            $this->cards->moveCard($selectedCard[Game::CARD_KEY_ID], 'hand', $current_player_id);
        }
        $this->notifyHandler->notifyPlayerIfNotRobot($current_player_id, 'cardMoved', '', ['fromStock' => 'hand', 'toStock' => 'selectedhand', 'card' => $this->cards->getCard($card_id)]);
        $this->cards->moveCard($card_id, 'selectedhand', $current_player_id);
    }

}

?>

