<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

include_once(__DIR__.'/DatabaseInterface.php');

class UpdatePlayerRobotProperties extends \ArrayObject {

    static public function create($data) : UpdatePlayerRobotProperties {
        $object = new UpdatePlayerRobotProperties();
        return $object->setData($data);
    }

    public function setData($data) : UpdatePlayerRobotProperties {
        $this->data = $data;
        return $this;
    }

    public function setEventEmitter($event_handler) : UpdatePlayerRobotProperties {
        $this->event_handler = $event_handler;
        return $this;
    }
}
?>
