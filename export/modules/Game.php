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
    const CARDS_PLAYED_HAND = 'playedhand';
    const CARDS_BOARD_HAND = 'boardhand';
    const CARD_KEY_ID = 'id';
    const CARD_KEY_TYPE = 'type';

    static public function getCardDefinitions(): array {
        $cards = array ();
        for ($id = 0;  $id < Game::NUMBER_CARDS_INCLUDING_START; $id++ ) {
            if ($id != Game::INDEX_START_CARD) {
                $cards [] = array ('type' => $id,'type_arg' => 0,'nbr' => 1 );
            }
        }
        return $cards;
    }

    public static function create($sqlDatabase) : Game {
        $game = new Game();
        return $game->setDatabase($sqlDatabase);
    }

    public function setDatabase($sqlDatabase) : Game {
        $this->sqlDatabase = $sqlDatabase;
        return $this;
    }

    public function setCards($cards) : Game {
        $this->cards = $cards;
        return $this;
    }

    public function setNotifyInterface($notifyInterface) : Game {
        $this->notifyInterface = $notifyInterface;
        return $this;
    }

    public function setPlayerProperties(PlayerProperties $properties) {
        $this->playerProperties = $properties;
    }

    public function setFields(Fields $fields) {
        $this->fields = $fields;
    }

    public function setOcean(Ocean $ocean) {
        $this->ocean = $ocean;
    }

    public function notifyPlayerIfNotRobot($player_id, string $notification_type, string $notification_log, array $notification_args) : void {
        if ($this->playerProperties->isPlayerARobot($player_id)) {
            return;
        }
        $this->notifyInterface->notifyPlayer($player_id, $notification_type, $notification_log, $notification_args);
    }

    public function allRobotsPlayCard() {
        $this->sqlDatabase->trace( "allRobotsPlayCard" );
        foreach (Robot::create($this->playerProperties->getRobotProperties()) as $robot) {
            // $this->sqlDatabase->trace( "allRobotsPlayCard " . PlayerProperties::KEY_ID." ". $this->playerProperties->getRobotProperties()[0][PlayerProperties::KEY_ID]);
            $cards = $this->cards->getCardsInLocation(Game::CARDS_SELECTED_HAND, $robot->getPlayerID());
            $card = array_shift($cards);
            $this->robotPlayCard($robot, $card);
        }
    }
    private function robotPlayCard($robot, $card) {
        $card_id = $card[Game::CARD_KEY_ID];
        $this->cards->moveCard($card_id, Game::CARDS_PLAYED_HAND);
        $this->sqlDatabase->trace('getPlayerPosition ' . $robot->getPlayerID() . ' ' . $card[Game::CARD_KEY_TYPE] . ' '. $this->ocean->getPlayerPosition($robot->getPlayerID()));

        $fields = $this->ocean->getSelectableFields($robot->getPlayerID(), $card[Game::CARD_KEY_TYPE]);
        $id_within_category = $robot->selectField($fields);

        $this->cards->moveCard($card_id, Game::CARDS_HAND, -2);

        if ($this->processSelectedField($robot->getPlayerID(), $id_within_category)) {
            // Extra card
            $cards = $this->cards->getCardsInLocation(Game::CARDS_HAND, -1);
            $card = array_shift($cards);
            $this->robotPlayCard($robot, $card);
        }
    }

    public function processSelectedField($player_id, $id_within_category) {
        $reward = $this->ocean->getReward($player_id, $id_within_category);
        $this->ocean->setPlayerPosition($player_id, $id_within_category);

        $this->processRewardPoints($player_id, $reward['points']);

        return $reward['extra_card'];
    }

    private function processRewardPoints($player_id, $points) {
        if ($points != 0) {
            $this->playerProperties->addScore($player_id, $points);
        }
    }

    public function allRobotsSelectCard() {
        foreach (Robot::create($this->playerProperties->getRobotProperties()) as $robot) {
            $cards = $this->cards->getCardsInLocation(Game::CARDS_HAND, $robot->getPlayerID());
            $card_id = $robot->selectCard(array_column($cards, Game::CARD_KEY_ID));
            $this->moveFromHandToSelected($card_id, $robot->getPlayerID());
        }
    }

    public function moveFromHandToSelected($card_id, $current_player_id) {
        foreach ($this->cards->getCardsInLocation('selectedhand', $current_player_id) as $selectedCard) {
            $this->notifyPlayerIfNotRobot($current_player_id, 'cardMoved', '', ['fromStock' => 'selectedhand', 'toStock' => 'myhand', 'card' => $selectedCard]);
            $this->cards->moveCard($selectedCard[Game::CARD_KEY_ID], 'hand', $current_player_id);
        }
        $this->notifyPlayerIfNotRobot($current_player_id, 'cardMoved', '', ['fromStock' => 'myhand', 'toStock' => 'selectedhand', 'card' => $this->cards->getCard($card_id)]);
        $this->cards->moveCard($card_id, 'selectedhand', $current_player_id);
    }

    public function getTooltips() {
        return Ocean::PLACES_PER_CARD;
    }
}

?>
