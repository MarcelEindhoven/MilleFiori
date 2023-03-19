<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/BGA/CardsInterface.php');
require_once(__DIR__.'/BGA/DatabaseInterface.php');

include_once(__DIR__.'/Ocean.php');
include_once(__DIR__.'/Robot.php');
include_once(__DIR__.'/PlayerProperties.php');

class Game {
    const NUMBER_CARDS_INCLUDING_START = 110;
    const INDEX_START_CARD = 35;
    const CARDS_HAND = 'hand';
    const CARDS_SELECTED_HAND = 'selectedhand';
    const CARD_KEY_ID = 'id';

    static public function getCardDefinitions(): array {
        $cards = array ();
        for ($id = 0;  $id < Game::NUMBER_CARDS_INCLUDING_START; $id++ ) {
            if ($id != Game::INDEX_START_CARD) {
                $cards [] = array ('type' => $id,'type_arg' => 0,'nbr' => 1 );
            }
        }
        return $cards;
    }

    public static function create(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : Game {
        $game = new Game();
        return $game->setDatabase($sqlDatabase);
    }

    public function setDatabase(\NieuwenhovenGames\BGA\DatabaseInterface $sqlDatabase) : Game {
        $this->sqlDatabase = $sqlDatabase;
        return $this;
    }

    public function setCards($bgaCards) : Game {
        $this->bgaCards = $bgaCards;
        return $this;
    }

    public function setPlayerProperties(PlayerProperties $properties) {
        $this->playerProperties = $properties;
    }

    public function allRobotsSelectCard() {
        foreach (Robot::create($this->playerProperties->getRobotProperties()) as $robot) {
            $cards = $this->bgaCards->getCardsInLocation(Game::CARDS_HAND, $robot->getPlayerID());
            $cardID = $robot->selectCard(array_column($cards, Game::CARD_KEY_ID));
            $this->moveFromHandToSelected($cardID, $robot->getPlayerID());
        }
    }

    public function moveFromHandToSelected($card_id, $current_player_id) {
        foreach ($this->bgaCards->getCardsInLocation('selectedhand', $current_player_id) as $selectedCard) {
            // self::notifyPlayer($current_player_id, 'cardMoved', '', ['fromStock' => 'selectedhand', 'toStock' => 'myhand', 'cardID' => $selectedCard]);
            $this->bgaCards->moveCard($selectedCard['id'], 'hand', $current_player_id);
        }
        // self::notifyPlayer($current_player_id, 'cardMoved', '', ['fromStock' => 'myhand', 'toStock' => 'selectedhand', 'cardID' => $this->cards->getCard($card_id)]);
        $this->bgaCards->moveCard($card_id, 'selectedhand', $current_player_id);
    }

    public function getTooltips() {
        return Ocean::PLACES_PER_CARD;
    }
}

?>
