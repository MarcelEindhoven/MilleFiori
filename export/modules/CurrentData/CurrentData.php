<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../BGA/Deck.php');
require_once(__DIR__.'/../BGA/Storage.php');

include_once(__DIR__.'/../Categories.php');
include_once(__DIR__.'/CurrentCards.php');
include_once(__DIR__.'/CurrentOcean.php');
include_once(__DIR__.'/CurrentPlayerRobotProperties.php');
include_once(__DIR__.'/CurrentCategories.php');

class CurrentData {
    const CARDS_SELECTED_HAND = 'selectedhand';
    const CARDS_BOARD_HAND = 'sideboard';
    const CARD_KEY_ID = 'id';
    const CARD_KEY_TYPE = 'type';

    const RESULT_KEY_PLAYERS = 'players';
    const RESULT_KEY_PLAYERSROBOTS = 'playersIncludingRobots';
    const RESULT_KEY_SELECTABLE_FIELDS = 'selectableFields';
    const RESULT_KEY_TOOLTIPS_CARDS = 'tooltipsCards';

    public static function create($sql_database) : CurrentData {
        $object = new CurrentData();
        return $object->setDatabase($sql_database);
    }

    public function setDatabase($sql_database) : CurrentData {
        $storage = \NieuwenhovenGames\BGA\Storage::create($sql_database);
        $this->setStorage($storage);

        return $this;
    }

    public function setStorage($storage) : CurrentData {
        $this->storage = $storage;

        $player_robot_properties = CurrentPlayerRobotProperties::create($this->storage);
        $this->setPlayerRobotProperties($player_robot_properties);
 
        return $this;
    }

    public function setPlayerRobotProperties($player_robot_properties) : CurrentData {
        $this->player_robot_properties = $player_robot_properties;

        $this->all_data_common = [];
        $this->all_data_common[CurrentData::RESULT_KEY_PLAYERS] = $this->player_robot_properties->getPlayerData();
        $this->all_data_common[CurrentData::RESULT_KEY_PLAYERSROBOTS] = $this->all_data_common[CurrentData::RESULT_KEY_PLAYERS] + $this->player_robot_properties->getRobotData();
        $this->all_data_common[CurrentData::RESULT_KEY_SELECTABLE_FIELDS] = [];
        $this->all_data_common[CurrentData::RESULT_KEY_TOOLTIPS_CARDS] = CurrentOcean::getTooltipsCards();

        return $this;
    }

    public function setCards($cards) : CurrentData {
        $this->current_cards = CurrentCards::create($cards);
        return $this;
    }

    public function getPlayerRobotIDs(): array {
        return array_keys($this->all_data_common[CurrentData::RESULT_KEY_PLAYERSROBOTS]);
    }

    public function getPlayerIDs(): array {
        return array_keys($this->all_data_common[CurrentData::RESULT_KEY_PLAYERS]);
    }

    public function getRobotIDs(): array {
        return array_diff($this->getPlayerRobotIDs(), $this->getPlayerIDs());
    }

    public function getPlayerDataIncludingRobots(): array {
        return $this->all_data_common[CurrentData::RESULT_KEY_PLAYERSROBOTS];
    }

    public function getHand($player_id) {
        return $this->current_cards->getHand($player_id);
    }

    public function getAllData($player_id) : array {
        return $this->all_data_common + $this->current_cards->getHands($player_id);
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

    public function getSelectableFieldIDs($player_id) : array {
        $card_type_being_played = $this->current_cards->getOnlyCardFromPlayingHand()[Game::CARD_KEY_TYPE];

        $categories = CurrentCategories::create($this->all_data_common[CurrentData::RESULT_KEY_PLAYERSROBOTS]);

        return $categories->getSelectableFieldIDs($player_id, $card_type_being_played);
    }

    public function setFields(Fields $fields) {
        $this->fields = $fields;
    }

    public function setCategories(Categories $categories) {
        $this->categories = $categories;
    }

    public function getTooltips() {
        return CurrentOcean::getTooltips();
    }
}

?>
