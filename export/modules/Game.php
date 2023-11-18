<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/BGA/FrameworkInterfaces/Deck.php');
require_once(__DIR__.'/BGA/FrameworkInterfaces/Database.php');
require_once(__DIR__.'/BGA/CurrentPlayerOrRobot.php');
include_once(__DIR__.'/BGA/EventEmitter.php');
include_once(__DIR__.'/BGA/PlayerProperty.php');
include_once(__DIR__.'/BGA/PlayerRobotBucketNotifications.php');
include_once(__DIR__.'/BGA/PlayerRobotNotifications.php');
include_once(__DIR__.'/BGA/RewardHandler.php');
include_once(__DIR__.'/BGA/StockHandler.php');
include_once(__DIR__.'/BGA/UpdatePlayerRobotProperties.php');
include_once(__DIR__.'/BGA/UpdateStorage.php');

include_once(__DIR__.'/Ocean.php');
include_once(__DIR__.'/Categories.php');
include_once(__DIR__.'/ActionsAndStates/ActionActivatePlayerOrRobot.php');
include_once(__DIR__.'/ActionsAndStates/ActionEndPlayerTurn.php');
include_once(__DIR__.'/ActionsAndStates/ActionEndRound.php');
include_once(__DIR__.'/ActionsAndStates/ActionPlayerPlaysCard.php');
include_once(__DIR__.'/ActionsAndStates/ActionNewHand.php');
include_once(__DIR__.'/ActionsAndStates/ActionPlayerSelectsField.php');
include_once(__DIR__.'/ActionsAndStates/ActionRobotPlaysCardSelectsField.php');
include_once(__DIR__.'/ActionsAndStates/ActionRobotSelectsCard.php');
include_once(__DIR__.'/ActionsAndStates/ActionRobotsSelectCard.php');
include_once(__DIR__.'/ActionsAndStates/ActionPlayerSelectsCard.php');
include_once(__DIR__.'/ActionsAndStates/UpdateCards.php');
include_once(__DIR__.'/ActionsAndStates/UpdateOcean.php');
include_once(__DIR__.'/ActionsAndStates/RobotHandler.php');
include_once(__DIR__.'/CurrentData/CurrentData.php');

class Game {
    public static function create($sqlDatabase) : Game {
        $object = new Game();
        return $object->setDatabase($sqlDatabase);
    }

    public function setDatabase($sqlDatabase) : Game {
        $this->sqlDatabase = $sqlDatabase;
        $this->data_handler = CurrentData::create($this->sqlDatabase);

        $this->event_emitter = new \NieuwenhovenGames\BGA\EventEmitter();
        $this->update_storage = \NieuwenhovenGames\BGA\UpdateStorage::create($this->sqlDatabase);
        $this->update_storage->setEventEmitter($this->event_emitter);

        $this->player_properties = new \NieuwenhovenGames\BGA\UpdatePlayerRobotProperties($this->data_handler->getPlayerDataIncludingRobots());
        $this->player_properties->setEventEmitter($this->event_emitter);
        $this->player_properties->setPublicMessageWhenUpdated(\NieuwenhovenGames\BGA\UpdatePlayerRobotProperties::KEY_SCORE, '${player_name} score becomes ');

        $this->reward_handler = \NieuwenhovenGames\BGA\RewardHandler::createFromPlayerProperties($this->player_properties);;
        $this->reward_handler->setEventEmitter($this->event_emitter);

        // To be transformed into update categories
        $this->ocean_positions = \NieuwenhovenGames\BGA\PlayerProperty::createFromPlayerProperties(Ocean::KEY_PLAYER_POSITION, $this->player_properties);
        $this->update_ocean = UpdateOcean::create($this->ocean_positions);
        $this->update_ocean->setRewardHandler($this->reward_handler);

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
        $this->update_cards->setPlayerIDs($this->data_handler->getPlayerRobotIDs());
        $this->update_cards->setCardNamePerType($this->data_handler->getCardNamePerType());

        return $this;
    }

    public function setCurrentPlayerID($current_player_id) : Game {
        $this->current_player_or_robot = \NieuwenhovenGames\BGA\CurrentPlayerOrRobot::create($current_player_id);
        $this->current_player_or_robot->setPlayerAndRobotProperties($this->data_handler->getPlayerDataIncludingRobots());

        $this->current_player_or_robot->setGameState($this->gamestate);
        return $this;
    }

    public function setNotifications($notifyInterface) : Game {
        $this->notifyInterface = $notifyInterface;

        $this->notifyHandler = NotifyHandler::create($notifyInterface);
        $this->update_cards->setNotifyHandler($this->notifyHandler);

        $this->notifications_handler = \NieuwenhovenGames\BGA\PlayerRobotNotifications::create($notifyInterface, $this->data_handler->getPlayerDataIncludingRobots());
        $this->player_robot_bucket_notifications = \NieuwenhovenGames\BGA\PlayerRobotBucketNotifications::create($this->notifications_handler);
        $this->player_robot_bucket_notifications->setEventEmitter($this->event_emitter);

        $this->stockHandler = \NieuwenhovenGames\BGA\StockHandler::create($this->notifications_handler);
        $this->update_cards->setStockHandler($this->stockHandler);
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
        ActionNewHand::create($this->gamestate)->setCardsHandler($this->update_cards)->setCardSelectionSimultaneous($this->is_card_selection_simultaneous)->execute()->nextState();
    }

    public function stRobotsSelectCard() {
        ActionRobotsSelectCard::create($this->gamestate)->setRobotHandler($this->robot_handler)->setCardsHandler($this->update_cards)->execute()->nextState();
    }

    public function stRobotSelectsCard() {
        $robot = $this->robot_handler->setCurrentPlayerID($this->current_player_or_robot->getCurrentPlayerOrRobotID())->getCurrentRobot();
        ActionRobotSelectsCard::create($this->gamestate)->setRobot($robot)->setCardsHandler($this->update_cards)->execute()->nextState();
    }

    public function stSelectsField($action) {
        $action->setEventEmitter($this->event_emitter)->setCardsHandler($this->update_cards)->setFieldSelectionHandler($this->update_ocean)->execute()->nextState();;
    }

    public function stRobotPlaysCardSelectsField() {
        $robot = $this->robot_handler->setCurrentPlayerID($this->current_player_or_robot->getCurrentPlayerOrRobotID())->getCurrentRobot();

        $this->stSelectsField(ActionRobotPlaysCardSelectsField::create($this->gamestate)->setDataHandler($this->data_handler)->setRobot($robot));
    }

    public function stActivatePlayerOrRobot() {
        ActionActivatePlayerOrRobot::create($this->gamestate)->setCurrentPlayerOrRobot($this->current_player_or_robot)->setCardSelectionSimultaneous($this->is_card_selection_simultaneous)->execute()->nextState();
    }

    public function stPlayerPlaysCard() {
        ActionPlayerPlaysCard::create($this->gamestate)->setDataHandler($this->data_handler)->setCardsHandler($this->update_cards)->setNotifyHandler($this->notifyInterface)->setCurrentPlayerID($this->current_player_or_robot->getCurrentPlayerOrRobotID())->execute();
    }

    public function stEndOfTurn() {
        ActionEndPlayerTurn::create($this->gamestate)->setCardsHandler($this->update_cards)->setCurrentPlayerOrRobot($this->current_player_or_robot)->execute()->nextState();
    }

    public function stEndOfRound() {
        ActionEndRound::create($this->gamestate)->setCardsHandler($this->update_cards)->setCardSelectionSimultaneous($this->is_card_selection_simultaneous)->execute()->nextState();
    }

    public function playerSelectsCard($player_id, $card_id) {
        ActionPlayerSelectsCard::create($this->gamestate)->setCardsHandler($this->update_cards)->setPlayerAndCard($player_id, $card_id)->execute()->nextState();
    }

    public function playerSelectsField($player_id, $field_id) {
        $this->stSelectsField(ActionPlayerSelectsField::create($this->gamestate)->setNotifyHandler($this->notifyInterface)->setPlayerAndField($player_id, $field_id));
    }
}

?>
