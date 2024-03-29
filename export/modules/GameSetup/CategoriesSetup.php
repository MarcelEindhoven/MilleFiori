<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * 
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../Categories.php');

class CategoriesSetup extends Categories {
    public function getAllCompleteFieldIDsForOccupation() : array {
        $ids = array();
        foreach ($this->categories as $category) {
            if (method_exists($category, 'getAllFieldIDsForOccupation')) {
                $ids = array_merge($ids, $this->completeIDs($category->getCategoryID(), $category->getAllFieldIDsForOccupation()));
            }
        }

        return $ids;
    }
}

?>
