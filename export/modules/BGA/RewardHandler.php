<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class RewardHandler {
    static public function createFromPlayerProperties($data) : RewardHandler {
        $object = new RewardHandler();
        return $object->setData($data);
    }

    public function setData($data) : RewardHandler {
        $this->data = $data;
        return $this;
    }

    public function setEventEmitter($event_handler) : RewardHandler {
        $this->event_handler = $event_handler;
        return $this;
    }

    public function gainedPoints($player_id, $points) {
        $this->data[$player_id][UpdatePlayerRobotProperties::KEY_SCORE] += $points;
    }

    public function gainedAdditionalReward($player_id, $additional_reward) {
        $this->event_handler->emit($additional_reward, []);
    }
}

?>
