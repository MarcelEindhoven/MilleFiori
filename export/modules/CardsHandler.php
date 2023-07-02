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

require_once(__DIR__.'/BGA/Deck.php');
include_once(__DIR__.'/BGA/Notifications.php');

class CardsHandler {
    const LOCATION_SWAP = -3;
    const SELECTED_HAND = 'selectedhand';
    const PLAYED_HAND = 'playedhand';
    const SIDEBOARD = 'sideboard';

    static public function create($cards) : CardsHandler {
        $cardsHandler = new CardsHandler();
        return $cardsHandler->setCards($cards);
    }

    public function setCards($cards) : CardsHandler {
        $this->cards = $cards;
        return $this;
    }

    public function getNumberSelectedCards() {
        return $this->getNumberCards(CardsHandler::SELECTED_HAND);
    }

    public function getNumberPlayerCards() {
        return $this->getNumberCards(\NieuwenhovenGames\BGA\Deck::PLAYER_HAND);
    }

    public function getNumberDeckCards() {
        return $this->getNumberCards(\NieuwenhovenGames\BGA\Deck::STANDARD_DECK);
    }

    public function getNumberCards($location) {
        $total_cards = 0;
        foreach ($this->cards->countCardsByLocationArgs($location) as $number_cards) {
            $total_cards += $number_cards;
        }
        return $total_cards;
    }

    public function getOnlyCardFromPlayerHand($player_id) : array {
        return $this->getOnlyCardFromLocation(\NieuwenhovenGames\BGA\Deck::PLAYER_HAND, $player_id);
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

    public function dealNewHand($player_id, $number_cards) {
        $this->cards->pickCards($number_cards, \NieuwenhovenGames\BGA\Deck::STANDARD_DECK, $player_id);
    }

    public function getSideboard() {
        return $this->cards->getCardsInLocation(CardsHandler::SIDEBOARD);
    }

    public function getHand($player_id) {
        return $this->cards->getCardsInLocation(\NieuwenhovenGames\BGA\Deck::PLAYER_HAND, $player_id);
    }

}

?>

