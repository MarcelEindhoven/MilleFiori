<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 * Fill the database
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

require_once(__DIR__.'/../BGA/Storage.php');
include_once(__DIR__.'/CategoriesSetup.php');
include_once(__DIR__.'/HousesSetup.php');
include_once(__DIR__.'/FieldsSetup.php');

class GameSetup {
    const NOT_OCCUPIED = -1;
    const BUCKET_NAME = 'field';
    const FIELD_ID_NAME = 'field_id';
    const PLAYER_ID_NAME = 'player_id';

    public static function create($sqlDatabase) : GameSetup {
        $storage = \NieuwenhovenGames\BGA\Storage::create($sqlDatabase);
        $fields_setup = FieldsSetup::create($storage);

        $categories_setup = new CategoriesSetup();
        $categories_setup->setCategories([new HousesSetup()]);

        $object = new GameSetup();
        return $object->setFieldsSetup($fields_setup)->setCategoriesSetup($categories_setup);
    }

    public function setFieldsSetup($fields_setup) : GameSetup {
        $this->fields_setup = $fields_setup;
        return $this;
    }

    public function setCategoriesSetup($categories_setup) : GameSetup {
        $this->categories_setup = $categories_setup;
        return $this;
    }

    public function setup() {
        $this->fields_setup->setup($this->categories_setup->getAllCompleteFieldIDsForOccupation());
    }
}

?>
