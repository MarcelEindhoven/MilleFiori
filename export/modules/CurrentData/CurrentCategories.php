<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../Categories.php');

class CurrentCategories extends Categories {

    public static function create($all_data) : CurrentCategories {
        $object = new CurrentCategories();
        return $object->setData($all_data);
    }

    public function setData($all_data) : CurrentCategories {
        $this->all_data = $all_data;
        return $this;
    }

    public function getSelectableFields() : array {
        return [];
    }
}

?>
