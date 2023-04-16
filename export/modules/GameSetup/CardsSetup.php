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
    const NUMBER_CARDS_INCLUDING_START = 110;
    const INDEX_START_CARD = 35;

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

    public function createAndShuffle() : CardsSetup {
        $this->cards->createCards($this->getCardDefinitions(), CardsHandler::DECK);

        $this->cards->shuffle(CardsHandler::DECK);

        return $this;
    }

    public function getCardDefinitions(): array {
        $cards = array ();
        for ($i = 0;  $i < CardsSetup::NUMBER_CARDS_INCLUDING_START; $i++ ) {
            if ($i != CardsSetup::INDEX_START_CARD) {
                $cards [] = array ('type' => $i,'type_arg' => 0,'nbr' => 1 );
            }
        }
        return $cards;
    }
}

?>

