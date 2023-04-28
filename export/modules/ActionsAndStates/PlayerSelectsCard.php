<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */


class PlayerSelectsCard {
    public static function create() : PlayerSelectsCard {
        $object = new PlayerSelectsCard();
        return $object;
    }

    public function setGameState($gamestate) : PlayerSelectsCard {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function setPlayerAndCard($player_id, $card_id) : PlayerSelectsCard {
        $this->player_id = $player_id;
        $this->card_id = $card_id;

        return $this;
    }

    public function setCardsHandler($cards_handler) : PlayerSelectsCard {
        $this->cards_handler = $cards_handler;
        return $this;
    }

    public function execute() : PlayerSelectsCard {
        $this->cards_handler->moveFromHandToSelected($this->card_id, $this->player_id);

        return $this;
    }

    public function nextState() : PlayerSelectsCard {
        $this->gamestate->nextState();

        return $this;
    }
}

?>
