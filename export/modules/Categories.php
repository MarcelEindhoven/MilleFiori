<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/Ocean.php');
include_once(__DIR__.'/Houses.php');

class Categories {
    const KEY_CATEGORY = 'CATEGORY';

    public static function create($player_properties): Categories {
        $categories = new Categories();

        $categories->setCategories([Ocean::KEY_CATEGORY => Ocean::create($player_properties), Houses::KEY_CATEGORY => new Houses(), ]);

        return $categories;
    }

    public function setCategories(array $categories): Categories {
        $this->categories = $categories;

        return $this;
    }

    public function getFieldsIncludingCategory(string $category, array $fields) : array {
        $updated_fields = array();

        foreach($fields as $field) {
            $field[Categories::KEY_CATEGORY] = $category;
            $updated_fields[] = $field;
        }

        return $updated_fields;
    }

    public function generateFields() : array {
        $fields = array();

        foreach ($this->categories as $category) {
            $fields = array_merge($fields, $this->getFieldsIncludingCategory($category->getCategory(), $category->generateFields()));
        }

        return $fields;
    }

    public function getSelectableFields($player, int $card_type) : array {
        $fields = array();

        foreach ($this->categories as $category) {
            $fields = array_merge($fields, $this->getFieldsIncludingCategory($category->getCategory(), $category->getSelectableFields($player, $card_type)));
        }

        return $fields;
    }
}

?>
