<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

class EventEmitter {
    protected array $subscriptions = [];

    public function on($channel, $callable) {
        $this->subscriptions[] = [$channel, $callable, false];
    }

    public function once($channel, $callable) {
        $this->subscriptions[] = [$channel, $callable, true];
    }

    public function off($channel, $callable) {
        foreach ($this->subscriptions as $key => [$subscription_channel, $subscription_callable, $callOnlyOnce]) {
            if (($channel == $subscription_channel) && ($callable == $subscription_callable)) {
                unset($this->subscriptions[$key]);
            }
        }
    }

    public function emit($channel, $event) {
        foreach ($this->subscriptions as $key => [$subscription_channel, $callable, $callOnlyOnce]) {
            if ($channel == $subscription_channel) {
                $callable($event);

                if ($callOnlyOnce) {
                    unset($this->subscriptions[$key]);
                }
            }
        }
    }
}
?>
