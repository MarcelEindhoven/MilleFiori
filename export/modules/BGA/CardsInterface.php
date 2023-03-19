<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

interface CardsInterface {
    public function getCardsInLocation(string $location, int $locationIndex = null) : array;
    public function moveCard(int $cardID, string $location, int $locationIndex = null) : void;
}
?>
