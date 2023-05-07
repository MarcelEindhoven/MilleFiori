<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/ViewOcean.php');
include_once(__DIR__.'/ViewHouses.php');

class ViewCategories {
    const FIELD_PREFIX = 'field_';
    const FIELD_SEPARATOR = '_';

    const KEY_CATEGORY = 'CATEGORY';
    const KEY_FIELD_ID = 'field_id';
    const KEY_ID = 'ID';

    public static function create(): ViewCategories {
        $object = new ViewCategories();

        $object->setViewCategories([ViewOcean::KEY_CATEGORY => new ViewOcean(), ViewHouses::KEY_CATEGORY => new ViewHouses(), ]);

        return $object;
    }

    public function setViewCategories(array $categories): ViewCategories {
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
        return ViewCategories::FIELD_PREFIX . $category . ViewCategories::FIELD_SEPARATOR . $id;
    }

    protected function getFieldsIncludingCategory(string $category, array $fields) : array {
        $updated_fields = array();

        foreach($fields as $field) {
            $field[ViewCategories::KEY_CATEGORY] = $category;
            $field[ViewCategories::KEY_FIELD_ID] = Fields::completeID($category, $field[ViewCategories::KEY_ID]);
            $updated_fields[] = $field;
        }

        return $updated_fields;
    }

    public function generateFields() : array {
        $fields = array();

        foreach ($this->categories as $category) {
            $fields = array_merge($fields, $this->getFieldsIncludingCategory($category->getCategoryID(), $category->generateFields()));
        }

        return $fields;
    }
}

?>
