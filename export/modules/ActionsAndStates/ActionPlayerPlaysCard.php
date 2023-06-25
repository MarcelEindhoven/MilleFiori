<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../BGA/Action.php');

class ActionPlayerPlaysCard extends \NieuwenhovenGames\BGA\Action {

    public static function create($gamestate) : ActionPlayerPlaysCard {
        return new ActionPlayerPlaysCard($gamestate);
    }

    public function setCardsHandler($cards_handler) : ActionPlayerPlaysCard {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function setDataHandler($data_handler) : ActionPlayerPlaysCard {
        $this->data_handler = $data_handler;
        return $this;
    }

    public function setNotifyHandler($notify_handler) : ActionPlayerPlaysCard {
        $this->notify_handler = $notify_handler;
        return $this;
    }

    public function setCurrentPlayerID($player_id) : ActionPlayerPlaysCard {
        $this->player_id = $player_id;
        return $this;
    }

    public function execute() : ActionPlayerPlaysCard {
        // Move from selected to played
        $this->cards_handler->playSelectedCard($this->player_id);

        // Activate selectable fields
        $this->notify_handler->notifyPlayer($this->player_id, 'selectableFields', '', ['selectableFields' => $this->data_handler->getSelectableFieldIDs($this->player_id)]);

        return $this;
    }
}

?>
