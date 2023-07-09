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
include_once(__DIR__.'/../CardsHandler.php');

class CurrentCards extends CardsHandler {
    static public function create($cards) : CurrentCards {
        $object = new CurrentCards();
        return $object->setCards($cards);
    }

    public function setCards($cards) : CurrentCards {
        $this->cards = $cards;
        return $this;
    }

    public function getHands($player_id) {
        // Private hands
        $hands = [];
        $hands[\NieuwenhovenGames\BGA\Deck::PLAYER_HAND] = $this->cards->getCardsInLocation(\NieuwenhovenGames\BGA\Deck::PLAYER_HAND, $player_id );
        $hands[CardsHandler::SELECTED_HAND] = $this->cards->getCardsInLocation(CardsHandler::SELECTED_HAND, $player_id );
        // Public hands
        $hands[\NieuwenhovenGames\BGA\Deck::DISCARD_PILE] = $this->cards->getCardsInLocation(\NieuwenhovenGames\BGA\Deck::DISCARD_PILE);
        $hands[CardsHandler::SIDEBOARD] = $this->cards->getCardsInLocation(CardsHandler::SIDEBOARD);
        $hands[CardsHandler::PLAYED_HAND] = $this->cards->getCardsInLocation(CardsHandler::PLAYED_HAND);
        $result['hands'] = $hands;

        return $result;
    }
}
?>

