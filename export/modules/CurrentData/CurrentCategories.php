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
include_once(__DIR__.'/CurrentOcean.php');

class CurrentCategories extends Categories {

    public static function create($player_robot_data) : CurrentCategories {
        $object = new CurrentCategories();
        return $object->setData($player_robot_data);
    }

    public function setData($player_robot_data) : CurrentCategories {
        $this->player_robot_data = $player_robot_data;

        $this->setCategories([Ocean::KEY_CATEGORY => CurrentOcean::create($player_robot_data), Houses::KEY_CATEGORY => new Houses(), ]);

        return $this;
    }

    public function getSelectableFieldIDs($player, int $card_type) : array {
        $fields = array();

        foreach ($this->categories as $category) {
            $fields = array_merge($fields, Fields::completeIDs($category->getCategory(), $category->getSelectableFieldIDs($player, $card_type)));
        }

        return $fields;
    }
}

?>
