<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

interface CurrentCategory {
    public function getSelectableFieldIDs($player, int $card_id) : array;
    public function getReward($player, $chosen_id) : array;
}

?>
