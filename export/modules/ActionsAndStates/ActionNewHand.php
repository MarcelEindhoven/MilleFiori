<?php
namespace NieuwenhovenGames\MilleFiori;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/../BGA/CardsInterface.php');
require_once(__DIR__.'/../BGA/DatabaseInterface.php');
require_once(__DIR__.'/../BGA/Storage.php');

include_once(__DIR__.'/../Robot.php');


class ActionNewHand {

    public static function create($sqlDatabase) : ActionNewHand {
        $object = new ActionNewHand();
        return $object->setDatabase($sqlDatabase);
    }

    public function setDatabase($sqlDatabase) : ActionNewHand {

        return $this;
    }
}

?>
