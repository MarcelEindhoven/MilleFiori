<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Responsible for, when player selects a field
 * - Cleanup selectable field IDs in the user interface
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/ActionSelectsField.php');

class ActionPlayerSelectsField extends ActionSelectsField {
    protected bool $select_extra_card = false;

    public static function create($gamestate) : ActionPlayerSelectsField {
        return new ActionPlayerSelectsField($gamestate);
    }

    public function setNotifyHandler($notify_handler) : ActionPlayerSelectsField {
        $this->notify_handler = $notify_handler;
        return $this;
    }

    public function setPlayerAndField($player_id, $field_id) : ActionSelectsField {
        $this->player_id = $player_id;
        $this->field_id = $field_id;
        return $this;
    }

    protected function pre_execute() {
        $this->notify_handler->notifyPlayer($this->player_id, 'selectableFields', '', ['selectableFields' => []]);
    }
}

?>
