<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Responsible for, when player/robot selects a field
 * - Cleanup played card
 * - Choose next state depending on whether extra card was earned because of field selection
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../BGA/SubscribedAction.php');

class ActionSelectsField extends \NieuwenhovenGames\BGA\SubscribedAction {
    protected bool $select_extra_card = false;

    public static function create($gamestate) : ActionSelectsField {
        return new ActionSelectsField($gamestate);
    }

    public function setCardsHandler($cards_handler) : ActionSelectsField {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setFieldSelectionHandler($field_selection_handler) : ActionSelectsField {
        $this->field_selection_handler = $field_selection_handler;
        return $this;
    }

    public function selectExtraCard() {
        $this->select_extra_card = true;
    }

    public function execute() : ActionSelectsField {
        $this->pre_execute();

        $this->cards_handler->emptyPlayedHand();

        $this->subscribe('select_extra_card', 'selectExtraCard');
        $this->field_selection_handler->playerSelectsField($this->player_id, $this->field_id);

        return $this;
    }

    public function getTransitionName() : string {
        return $this->select_extra_card ? 'selectExtraCard' : 'turnEnded';
    }
}

?>
