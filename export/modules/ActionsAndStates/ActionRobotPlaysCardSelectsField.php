<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Responsible for, when robot may play a card
 * - Moving the previously selected card into the played card section
 * - Selecting a field
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/ActionSelectsField.php');

class ActionRobotPlaysCardSelectsField extends ActionSelectsField {
    protected bool $select_extra_card = false;

    public static function create($gamestate) : ActionRobotPlaysCardSelectsField {
        return new ActionRobotPlaysCardSelectsField($gamestate);
    }

    public function setDataHandler($data_handler) : ActionRobotPlaysCardSelectsField {
        $this->data_handler = $data_handler;
        return $this;
    }

    public function setRobot($robot) : ActionRobotPlaysCardSelectsField {
        $this->robot = $robot;
        return $this;
    }

    public function pre_execute(){
        $this->player_id = $this->robot->getPlayerID();

        // Move from selected to played
        $this->cards_handler->playSelectedCard($this->player_id);

        $selectable_field_ids = $this->data_handler->getSelectableFieldIDs($this->player_id);
        $this->field_id = $this->robot->selectField($selectable_field_ids);
    }
}

?>
