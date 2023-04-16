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
include_once(__DIR__.'/../CardsHandler.php');

class CurrentCards extends CardsHandler {
    const DECK = 'deck';
    const HAND = 'hand';
    const SELECTED_HAND = 'selectedhand';
    const PLAYED_HAND = 'playedhand';
    const SIDEBOARD = 'sideboard';
    const DISCARD_PILE = 'discard';

    static public function create($cards) : CurrentCards {
        $object = new CurrentCards();
        return $object->setCards($cards);
    }

    public function setCards($cards) : CurrentCards {
        $this->cards = $cards;
        return $this;
    }

    public function getHands($player_id) {
        $result['hand'] = $this->cards->getCardsInLocation(CardsHandler::HAND, $player_id );
        $result['selectedhand'] = $this->cards->getCardsInLocation(CardsHandler::SELECTED_HAND, $player_id );
        
        // Cards played beside the table
        $result['sideboard'] = $this->getSideboard();

        $result['playedhand'] = $this->cards->getCardsInLocation(CardsHandler::PLAYED_HAND);

        return $result;
    }
}
?>

