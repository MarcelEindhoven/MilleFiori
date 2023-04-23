<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../BGA/CardsInterface.php');
require_once(__DIR__.'/../BGA/DatabaseInterface.php');
require_once(__DIR__.'/../BGA/Storage.php');

include_once(__DIR__.'/../Robot.php');
include_once(__DIR__.'/../PlayerRobotProperties.php');
include_once(__DIR__.'/../Categories.php');
include_once(__DIR__.'/CurrentCards.php');
include_once(__DIR__.'/CurrentOcean.php');
include_once(__DIR__.'/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/CurrentCategories.php');

class CurrentData {
    const CARDS_HAND = 'hand';
    const CARDS_SELECTED_HAND = 'selectedhand';
    const CARDS_PLAYED_HAND = 'playedhand';
    const CARDS_BOARD_HAND = 'sideboard';
    const CARD_KEY_ID = 'id';
    const CARD_KEY_TYPE = 'type';

    const RESULT_KEY_PLAYERS = 'players';
    const RESULT_KEY_PLAYERSROBOTS = 'playersIncludingRobots';
    const RESULT_KEY_SELECTABLE_FIELDS = 'selectableFields';
    const RESULT_KEY_TOOLTIPS_CARDS = 'tooltipsCards';

    public static function create($sqlDatabase) : CurrentData {
        $object = new CurrentData();
        return $object->setDatabase($sqlDatabase);
    }

    public function setDatabase($sqlDatabase) : CurrentData {
        $this->storage = \NieuwenhovenGames\BGA\Storage::create($sqlDatabase);

        $this->playerProperties = CurrentPlayerRobotProperties::create($this->storage);

        return $this;
    }

    public function setCards($cards) : CurrentData {
        $this->current_cards = CurrentCards::create($cards);
        return $this;
    }

    public function setPlayerRobotProperties(PlayerRobotProperties $properties) : CurrentData {
        $this->playerProperties = $properties;
        return $this;
    }

    public function getAllData($player_id) : array {
        $result = $this->current_cards->getHands($player_id);

        $result[CurrentData::RESULT_KEY_PLAYERS] = $this->playerProperties->getPlayerData();
        $result[CurrentData::RESULT_KEY_PLAYERSROBOTS] = $result[CurrentData::RESULT_KEY_PLAYERS] + $this->playerProperties->getRobotData();
        $result[CurrentData::RESULT_KEY_SELECTABLE_FIELDS] = [];
        $result[CurrentData::RESULT_KEY_TOOLTIPS_CARDS] = CurrentOcean::getTooltipsCards();

        return $result;
    }

    public function getAllDataActivePlayerPlayingCard($player_id) : array {
        $result = $this->getAllData($player_id);

        $result[CurrentData::RESULT_KEY_SELECTABLE_FIELDS] = $this->getSelectableFieldIDsActivePlayerPlayingCard($player_id, $result[CurrentData::RESULT_KEY_PLAYERSROBOTS]);

        return $result;
    }

    public function getSelectableFieldIDsActivePlayerPlayingCard($player_id, $player_robot_data) : array {
        $card_type_being_played = $this->current_cards->getOnlyCardFromPlayingHand()[Game::CARD_KEY_TYPE];

        $categories = CurrentCategories::create($player_robot_data);

        return $categories->getSelectableFieldIDs($player_id, $card_type_being_played);
    }

    public function setFields(Fields $fields) {
        $this->fields = $fields;
    }

    public function setCategories(Categories $categories) {
        $this->categories = $categories;
    }

    public function notifyPlayerIfNotRobot($player_id, string $notification_type, string $notification_log, array $notification_args) : void {
        if ($this->playerProperties->isPlayerARobot($player_id)) {
            return;
        }
        $this->notifyInterface->notifyPlayer($player_id, $notification_type, $notification_log, $notification_args);
    }

    public function dealNewHand($number_cards) {
        foreach ($this->playerProperties->getPropertiesPlayersPlusRobots() as $player_id => $player) {
            $this->cards_handler->moveHandToSideboard($player_id);
            $this->cards_handler->dealNewHand($player_id, $number_cards);
        }
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
