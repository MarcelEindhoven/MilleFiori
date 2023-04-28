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

    public function getPlayerRobotIDs(): array {
        return array_keys($this->playerProperties->getPlayerDataIncludingRobots());
    }

    public function getPlayerIDs(): array {
        return array_keys($this->playerProperties->getPlayerData());
    }

    public function getRobotIDs(): array {
        return array_diff($this->getPlayerRobotIDs(), $this->getPlayerIDs());
    }

    public function getHand($player_id) {
        return $this->current_cards->getHand($player_id);
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

    public function getTooltips() {
        return CurrentOcean::getTooltips();
    }
}

?>
