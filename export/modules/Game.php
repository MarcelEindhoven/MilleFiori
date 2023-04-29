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
include_once(__DIR__.'/Categories.php');
include_once(__DIR__.'/ActionsAndStates/ActionNewHand.php');
include_once(__DIR__.'/ActionsAndStates/ActionRobotsSelectCard.php');
include_once(__DIR__.'/ActionsAndStates/PlayerSelectsCard.php');
include_once(__DIR__.'/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/ActionsAndStates/RobotHandler.php');
include_once(__DIR__.'/CurrentData/CurrentData.php');

class Game {
    const CARDS_HAND = 'hand';
    const CARDS_SELECTED_HAND = 'selectedhand';
    const CARDS_PLAYED_HAND = 'playedhand';
    const CARDS_BOARD_HAND = 'sideboard';
    const CARD_KEY_ID = 'id';
    const CARD_KEY_TYPE = 'type';

    public static function create($sqlDatabase) : Game {
        $object = new Game();
        return $object->setDatabase($sqlDatabase);
    }

    public function setDatabase($sqlDatabase) : Game {
        $this->sqlDatabase = $sqlDatabase;
        $this->data_handler = CurrentData::create($this->sqlDatabase);

        $this->robot_handler = RobotHandler::create();
        foreach ($this->data_handler->getRobotIDs() as $robot_id) {
            $this->robot_handler->createRobot($robot_id, $this->data_handler);
        }

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

    public function setFields(Fields $fields) {
        $this->fields = $fields;
    }

    public function setCategories(Categories $categories) {
        $this->categories = $categories;
    }

    public function setOcean(Ocean $ocean) {
        $this->ocean = $ocean;
    }

    public function setCardSelectionSimultaneous($is_card_selection_simultaneous) : Game {
        $this->is_card_selection_simultaneous = $is_card_selection_simultaneous;
        return $this;
    }

    public function stNewHand() {
        ActionNewHand::create($this->data_handler)->setCardsHandler($this->update_cards)->setGameState($this->gamestate)->setCardSelectionSimultaneous($this->is_card_selection_simultaneous)->execute()->nextState();
    }

    public function stRobotsSelectCard() {
        ActionRobotsSelectCard::create($this->gamestate)->setRobotHandler($this->robot_handler)->setCardsHandler($this->update_cards)->setGameState($this->gamestate)->execute()->nextState();
    }

    function playerSelectsCard($player_id, $card_id) {
        PlayerSelectsCard::create()->setCardsHandler($this->update_cards)->setGameState($this->gamestate)->setPlayerAndCard($player_id, $card_id)->execute()->nextState();
    }

    public function allRobotsPlayCard() {
        $this->sqlDatabase->trace( "allRobotsPlayCard" );
        foreach (Robot::create($this->playerProperties->getRobotProperties()) as $robot) {
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

    public function getTooltips() {
        return Ocean::PLACES_PER_CARD;
    }
}

?>
