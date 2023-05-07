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
include_once(__DIR__.'/Fields.php');

class Categories {
    const FIELD_PREFIX = 'field_';
    const FIELD_SEPARATOR = '_';

    const KEY_CATEGORY = 'CATEGORY';
    const KEY_FIELD_ID = 'field_id';
    const KEY_ID = 'ID';

    public static function create($player_properties): Categories {
        $categories = new Categories();

        $categories->setCategories([Ocean::KEY_CATEGORY => Ocean::create($player_properties), Houses::KEY_CATEGORY => new Houses(), ]);

        return $categories;
    }

    public function setCategories(array $categories): Categories {
        $this->categories = $categories;

        return $this;
    }

    public function completeIDs(string $category, array $ids) : array {
        $completeIDs = [];
        foreach ($ids as $id) {
            $completeIDs[] = $this->completeID($category, $id);
        }
        return $completeIDs;
    }

    public function completeID(string $category, string $id) : string {
        return Categories::FIELD_PREFIX . $category . Categories::FIELD_SEPARATOR . $id;
    }

    protected function getFieldsIncludingCategory(string $category, array $fields) : array {
        $updated_fields = array();

        foreach($fields as $field) {
            $field[Categories::KEY_CATEGORY] = $category;
            $field[Categories::KEY_FIELD_ID] = Fields::completeID($category, $field[Categories::KEY_ID]);
            $updated_fields[] = $field;
        }

        return $updated_fields;
    }

    public function getSelectableFieldIDs($player, int $card_type) : array {
        $fields = array();

        foreach ($this->categories as $category) {
            $fields = array_merge($fields, Fields::completeIDs($category->getCategoryID(), $category->getSelectableFieldIDs($player, $card_type)));
        }

        return $fields;
    }
}

?>
