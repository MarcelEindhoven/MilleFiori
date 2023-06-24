<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Responsible for, when player selects a field
 * - Selecting a field
 * - Cleanup played card
 * - Activate game object
 * - Choose next state depending on whether extra card was earned
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../BGA/Action.php');

class ActionRobotPlaysCardSelectsField extends \NieuwenhovenGames\BGA\Action {
    protected bool $select_extra_card = false;

    public static function create($gamestate) : ActionRobotPlaysCardSelectsField {
        $object = new ActionRobotPlaysCardSelectsField();
        return $object->setGameState($gamestate);
    }

    public function setCardsHandler($cards_handler) : ActionRobotPlaysCardSelectsField {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setDataHandler($data_handler) : ActionRobotPlaysCardSelectsField {
        $this->data_handler = $data_handler;
        return $this;
    }

    public function setFieldSelectionHandler($field_selection_handler) : ActionRobotPlaysCardSelectsField {
        $this->field_selection_handler = $field_selection_handler;
        return $this;
    }

    public function setRobot($robot) : ActionRobotPlaysCardSelectsField {
        $this->robot = $robot;
        return $this;
    }

    public function selectExtraCard() {
        $this->select_extra_card = true;
    }

    public function execute() : ActionRobotPlaysCardSelectsField {
        // Move from selected to played
        $this->cards_handler->playSelectedCard($this->robot->getPlayerID());

        $selectable_field_ids = $this->data_handler->getSelectableFieldIDs($this->robot->getPlayerID());
        $this->field_id = $this->robot->selectField($selectable_field_ids);

        $this->cards_handler->emptyPlayedHand();

        $this->field_selection_handler->playerSelectsField($this->robot->getPlayerID(), $this->field_id);

        return $this;
    }

    public function getTransitionName() : string {
        return $this->select_extra_card ? 'selectExtraCard' : 'turnEnded';
    }
}

?>
