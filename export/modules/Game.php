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
include_once(__DIR__.'/PlayerRobotProperties.php');
include_once(__DIR__.'/Categories.php');
include_once(__DIR__.'/ActionsAndStates/ActionNewHand.php');
include_once(__DIR__.'/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/CurrentData/CurrentData.php');

class Game {
    const CARDS_HAND = 'hand';
    const CARDS_SELECTED_HAND = 'selectedhand';
    const CARDS_PLAYED_HAND = 'playedhand';
    const CARDS_BOARD_HAND = 'sideboard';
    const CARD_KEY_ID = 'id';
    const CARD_KEY_TYPE = 'type';

    public static function create($sqlDatabase) : Game {
        $game = new Game();
        return $game->setDatabase($sqlDatabase);
    }

    public function setDatabase($sqlDatabase) : Game {
        $this->sqlDatabase = $sqlDatabase;
        $this->data_handler = CurrentData::create($this->sqlDatabase);
        return $this;
    }

    public function setCards($cards) : Game {
        $this->cards = $cards;
        $this->data_handler->setCards($this->cards);
        $this->update_cards = UpdateCards::create($this->cards);
        return $this;
    }

    public function setCardsHandler($cards_handler) : Game {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setNotifyInterface($notifyInterface) : Game {
        $this->notifyInterface = $notifyInterface;
        $this->notifyHandler = NotifyHandler::create($notifyInterface);
        $this->update_cards->setNotifyHandler($this->notifyHandler);
        return $this;
    }

    public function setGameState($gamestate) : Game {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function setPlayerRobotProperties(PlayerRobotProperties $properties) {
        $this->playerProperties = $properties;
    }

    public function setFields(Fields $fields) {
        $this->fields = $fields;
    }

    public function setCategories(Categories $categories) {
        $this->categories = $categories;
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

    public function stNewHand() {
        ActionNewHand::create($this->data_handler)->setCardsHandler($this->update_cards)->setGameState($this->gamestate)->execute();
    }

    public function allRobotsPlayCard() {
        $this->sqlDatabase->trace( "allRobotsPlayCard" );
        foreach (Robot::create($this->playerProperties->getRobotProperties()) as $robot) {
            // $this->sqlDatabase->trace( "allRobotsPlayCard " . PlayerRobotProperties::KEY_ID." ". $this->playerProperties->getRobotProperties()[0][PlayerRobotProperties::KEY_ID]);
            $cards = $this->cards->getCardsInLocation(Game::CARDS_SELECTED_HAND, $robot->getPlayerID());
            $card = array_shift($cards);
            $this->robotPlayCard($robot, $card);
        }
    }
    private function robotPlayCard($robot, $card) {
        $card_id = $card[Game::CARD_KEY_ID];
        $this->cards->moveCard($card_id, Game::CARDS_PLAYED_HAND);
        $this->sqlDatabase->trace('getPlayerPosition ' . $robot->getPlayerID() . ' ' . $card[Game::CARD_KEY_TYPE] . ' '. $this->ocean->getPlayerPosition($robot->getPlayerID()));

        $fields = $this->categories->getSelectableFieldIDs($robot->getPlayerID(), $card[Game::CARD_KEY_TYPE]);
        $field_id = $robot->selectField($fields);

        if ($this->processSelectedField($robot->getPlayerID(), Fields::getID($field_id))) {
            // Extra card
            $cards = $this->cards_handler->getSideboard();
            $card = array_shift($cards);
            $this->robotPlayCard($robot, $card);
        }
    }

    public function processSelectedField($player_id, $id_within_category) {
        $reward = $this->ocean->getReward($player_id, $id_within_category);
        $this->ocean->setPlayerPosition($player_id, $id_within_category);

        $this->cards_handler->emptyPlayedHand();

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
            $this->notifyPlayerIfNotRobot($current_player_id, 'cardMoved', '', ['fromStock' => 'selectedhand', 'toStock' => 'hand', 'card' => $selectedCard]);
            $this->cards->moveCard($selectedCard[Game::CARD_KEY_ID], 'hand', $current_player_id);
        }
        $this->notifyPlayerIfNotRobot($current_player_id, 'cardMoved', '', ['fromStock' => 'hand', 'toStock' => 'selectedhand', 'card' => $this->cards->getCard($card_id)]);
        $this->cards->moveCard($card_id, 'selectedhand', $current_player_id);
    }

    public function getTooltips() {
        return Ocean::PLACES_PER_CARD;
    }
}

?>
