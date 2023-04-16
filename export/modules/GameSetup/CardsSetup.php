<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class CardsSetup extends CardsHandler {

    static public function create($cards) : CardsSetup {
        $object = new CardsSetup();
        return $object->setCards($cards);
    }

    public function setCards($cards) : CardsSetup {
        $this->cards = $cards;
        return $this;
    }

    public function initialiseSideboard($number_cards) {
        $dummy_id = CardsHandler::LOCATION_SWAP;
        $this->cards->pickCards($number_cards, 'deck', $dummy_id);
        $this->cards->moveAllCardsInLocation(CardsHandler::HAND, CardsHandler::SIDEBOARD, $dummy_id);
    }
}

?>

