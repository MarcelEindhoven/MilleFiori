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

class ActionPlayerSelectsCard extends \NieuwenhovenGames\BGA\Action {
    public static function create($gamestate) : ActionPlayerSelectsCard {
        return new ActionPlayerSelectsCard($gamestate);
    }

    public function setPlayerAndCard($player_id, $card_id) : ActionPlayerSelectsCard {
        $this->player_id = $player_id;
        $this->card_id = $card_id;

        return $this;
    }

    public function setCardsHandler($cards_handler) : ActionPlayerSelectsCard {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function execute() : ActionPlayerSelectsCard {
        $this->cards_handler->moveFromHandToSelected($this->card_id, $this->player_id);

        return $this;
    }
}

?>
