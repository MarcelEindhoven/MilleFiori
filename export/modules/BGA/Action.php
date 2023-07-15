<?php
namespace NieuwenhovenGames\BGA;
/**
 * https://boardgamearena.com/doc/Main_game_logic:_yourgamename.game.php#States_functions
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/GameState.php');

class Action {
    const DEFAULT_TRANSITION_NAME = '';

    function __construct($gamestate) {
        $this->gamestate = $gamestate;
    }

    public function setGameState($gamestate) : Action {
        $this->gamestate = $gamestate;
        return $this;
    }

    public function nextState() {
        $transition_name = method_exists($this, 'getTransitionName') ? $this->getTransitionName() : Action::DEFAULT_TRANSITION_NAME;

        $this->gamestate->nextState($transition_name);
    }
}
?>
