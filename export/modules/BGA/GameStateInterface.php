<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

interface GameStateInterface {
    public function changeActivePlayer( $player_id );
    public function setAllPlayersMultiactive();
    public function setAllPlayersNonMultiactive( $next_state );
    public function setPlayersMultiactive( $players, $next_state, $bExclusive = false );
    public function setPlayerNonMultiactive( $player_id, $next_state );
    public function getActivePlayerList();
    public function updateMultiactiveOrNextState( $next_state_if_none );
    public function isPlayerActive($player_id);
    public function nextState( $transition );
    public function checkPossibleAction( $action );
    public function state();
    public function state_id();
    public function isMutiactiveState();

    public function initializePrivateStateForAllActivePlayers();
    public function initializePrivateStateForPlayers($playerIds);
    public function initializePrivateState($playerId);
    public function nextPrivateStateForAllActivePlayers($transition);
    public function nextPrivateStateForPlayers($playerIds, $transition);
    public function nextPrivateState($playerId, $transition);
    public function unsetPrivateStateForAllPlayers();
    public function unsetPrivateStateForPlayers($playerIds, $transition);
    public function unsetPrivateState($playerId);
    public function setPrivateState($playerId, $newStateId);

    public function reloadState();
}
?>
