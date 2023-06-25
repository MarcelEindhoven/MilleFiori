<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Responsible for, when player selects a field
 * - Cleanup played card
 * - Cleanup selectable field IDs
 * - Activate game object
 * - Choose next state depending on whether extra card was earned
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../BGA/SubscribedAction.php');

class ActionPlayerSelectsField extends \NieuwenhovenGames\BGA\SubscribedAction {
    protected bool $select_extra_card = false;

    public static function create($gamestate) : ActionPlayerSelectsField {
        return new ActionPlayerSelectsField($gamestate);
    }

    public function setCardsHandler($cards_handler) : ActionPlayerSelectsField {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setFieldSelectionHandler($field_selection_handler) : ActionPlayerSelectsField {
        $this->field_selection_handler = $field_selection_handler;
        return $this;
    }

    public function setNotifyHandler($notify_handler) : ActionPlayerSelectsField {
        $this->notify_handler = $notify_handler;
        return $this;
    }

    public function setPlayerAndField($player_id, $field_id) : ActionPlayerSelectsField {
        $this->player_id = $player_id;
        $this->field_id = $field_id;
        return $this;
    }

    public function selectExtraCard() {
        $this->select_extra_card = true;
    }

    public function execute() : ActionPlayerSelectsField {
        $this->subscribe('selectExtraCard', 'select_extra_card');

        // Note: this is a side-effect of empty played hand
        $this->notify_handler->notifyPlayer($this->player_id, 'selectableFields', '', ['selectableFields' => []]);

        $this->cards_handler->emptyPlayedHand();

        $this->field_selection_handler->playerSelectsField($this->player_id, $this->field_id);

        return $this;
    }

    public function getTransitionName() : string {
        return $this->select_extra_card ? 'selectExtraCard' : 'turnEnded';
    }
}

?>
