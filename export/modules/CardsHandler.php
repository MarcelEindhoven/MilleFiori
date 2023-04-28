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

require_once(__DIR__.'/BGA/CardsInterface.php');
include_once(__DIR__.'/BGA/NotifyInterface.php');

class CardsHandler {
    const DECK = 'deck';
    const LOCATION_SWAP = -3;
    const HAND = 'hand';
    const SELECTED_HAND = 'selectedhand';
    const PLAYED_HAND = 'playedhand';
    const SIDEBOARD = 'sideboard';
    const DISCARD_PILE = 'discard';

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
        $this->notifyHandler->notifyCardMoved($this->cards->getCard($card_id), 'Playing extra card', CardsHandler::SIDEBOARD, CardsHandler::PLAYED_HAND);
    }

    public function emptyPlayedHand() {
        $this->cards->moveAllCardsInLocation(CardsHandler::PLAYED_HAND, CardsHandler::DISCARD_PILE);

        $this->notifyHandler->notifyEmptyPlayedHand();
    }

    public function getNumberSelectedCards() {
        return $this->getNumberCards(CardsHandler::SELECTED_HAND);
    }

    public function getNumberPlayerCards() {
        return $this->getNumberCards(CardsHandler::HAND);
    }

    public function getNumberCards($location) {
        $total_cards = 0;
        foreach ($this->cards->countCardsByLocationArgs($location) as $number_cards) {
            $total_cards += $number_cards;
        }
        return $total_cards;
    }

    public function getOnlyCardFromPlayerHand($player_id) : array {
        return $this->getOnlyCardFromLocation(CardsHandler::HAND, $player_id);
    }

    public function getOnlyCardFromPlayingHand() : array {
        return $this->getOnlyCardFromLocation(CardsHandler::PLAYED_HAND);
    }

    public function getOnlyCardFromSelectedHand($player_id) : array {
        return $this->getOnlyCardFromLocation(CardsHandler::SELECTED_HAND, $player_id);
    }

    public function getOnlyCardFromLocation($location, $location_arg = null) : array {
        $cards = $this->cards->getCardsInLocation($location, $location_arg);
        return $cards ? array_shift($cards) : null;
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

    public function dealNewHand($player_id, $number_cards) {
        $this->cards->pickCards($number_cards, CardsHandler::DECK, $player_id);
    }

    public function getSideboard() {
        return $this->cards->getCardsInLocation(CardsHandler::SIDEBOARD);
    }

    public function getHand($player_id) {
        return $this->cards->getCardsInLocation(CardsHandler::HAND, $player_id);
    }

}

?>

