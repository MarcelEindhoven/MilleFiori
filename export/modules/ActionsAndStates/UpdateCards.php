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

require_once(__DIR__.'/../BGA/Deck.php');
include_once(__DIR__.'/../BGA/Notifications.php');
include_once(__DIR__.'/../CardsHandler.php');

class UpdateCards extends CardsHandler {
    static public function create($cards) : UpdateCards {
        $cardsHandler = new UpdateCards();
        return $cardsHandler->setCards($cards);
    }

    public function setCards($cards) : UpdateCards {
        $this->cards = $cards;
        return $this;
    }

    public function setStockHandler($stockHandler) : UpdateCards {
        $this->stockHandler = $stockHandler;
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
        return 1 == count(array_unique(array_values($this->cards->countCardsByLocationArgs(\NieuwenhovenGames\BGA\Deck::PLAYER_HAND))));
    }

    public function areAnyCardsSelected() : bool {
        return (bool) $this->cards->countCardsByLocationArgs(CardsHandler::SELECTED_HAND);
    }

    public function swapHands() : UpdateCards {
        if (count($this->player_ids) < 2) {
            return $this;
        }
        $previous_player = CardsHandler::LOCATION_SWAP;
        foreach ($this->player_ids as $player_id) {
            $this->cards->moveAllCardsInLocation(\NieuwenhovenGames\BGA\Deck::PLAYER_HAND, \NieuwenhovenGames\BGA\Deck::PLAYER_HAND, $player_id, $previous_player);
            $previous_player = $player_id;
        }

        $this->cards->moveAllCardsInLocation(\NieuwenhovenGames\BGA\Deck::PLAYER_HAND, \NieuwenhovenGames\BGA\Deck::PLAYER_HAND, CardsHandler::LOCATION_SWAP, $previous_player);
        foreach ($this->player_ids as $player_id) {
            $this->stockHandler->setNewStockContent($player_id, \NieuwenhovenGames\BGA\Deck::PLAYER_HAND, $this->cards->getCardsInLocation(\NieuwenhovenGames\BGA\Deck::PLAYER_HAND, $player_id), 'Pass hand to other player');
        }

        return $this;
    }

    public function dealNewHands($number_cards) : UpdateCards {
        foreach ($this->player_ids as $player_id) {
            $this->cards->pickCards($number_cards, \NieuwenhovenGames\BGA\Deck::STANDARD_DECK, $player_id);
            $this->stockHandler->setNewStockContent($player_id, \NieuwenhovenGames\BGA\Deck::PLAYER_HAND, $this->cards->getCardsInLocation(\NieuwenhovenGames\BGA\Deck::PLAYER_HAND, $player_id), 'Deal new hand');
            }

        return $this;
    }

    public function moveHandsToSideboard() : UpdateCards {
        foreach ($this->player_ids as $player_id) {
            $this->movePrivateToPublic('Giving up own card', $player_id, \NieuwenhovenGames\BGA\Deck::PLAYER_HAND, CardsHandler::SIDEBOARD);
        }

        return $this;
    }

    public function moveFromHandToSelected($card_id, $current_player_id) {
        foreach ($this->cards->getCardsInLocation('selectedhand', $current_player_id) as $selectedCard) {
            $this->notifyHandler->notifyPlayerIfNotRobot($current_player_id, 'cardMoved', '', ['fromStock' => 'selectedhand', 'toStock' => \NieuwenhovenGames\BGA\Deck::PLAYER_HAND, 'card' => $selectedCard]);
            $this->cards->moveCard($selectedCard[Game::CARD_KEY_ID], \NieuwenhovenGames\BGA\Deck::PLAYER_HAND, $current_player_id);
        }
        $this->cards->moveCard($card_id, 'selectedhand', $current_player_id);
        $this->stockHandler->moveCard($current_player_id, \NieuwenhovenGames\BGA\Deck::PLAYER_HAND, CardsHandler::SELECTED_HAND, $this->cards->getCard($card_id), 'You selected');
    }

    public function playSelectedCard($player_id) {
        $this->movePrivateToPublic('Playing selected card', $player_id, CardsHandler::SELECTED_HAND, CardsHandler::PLAYED_HAND);
    }

    public function movePrivateToPublic($message, $player_id, $from, $to) {
        foreach ($this->cards->getCardsInLocation($from, $player_id) as $card) {
            $this->notifyHandler->notifyCardMovedFromPrivateToPublic($card, $message . $player_id , $player_id, $from, $to);
        }

        $this->cards->moveAllCardsInLocation($from, $to, $player_id);
    }

    public function selectExtraCard($card_id) {
        $this->cards->moveCard($card_id, CardsHandler::PLAYED_HAND);
        $this->notifyHandler->notifyCardMoved($this->cards->getCard($card_id), 'Playing extra card', CardsHandler::SIDEBOARD, CardsHandler::PLAYED_HAND);
    }

    public function emptyPlayedHand() {
        $this->cards->moveAllCardsInLocation(CardsHandler::PLAYED_HAND, \NieuwenhovenGames\BGA\Deck::DISCARD_PILE);

        $this->notifyHandler->notifyEmptyPlayedHand();
    }

}

?>

