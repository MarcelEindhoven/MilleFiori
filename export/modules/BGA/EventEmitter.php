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

    public function on($channel, callable $callable) {
        $this->subscriptions[] = [$channel, $callable];
    }

    public function emit($channel, $event) {
        foreach($this->subscriptions as [$subscription_channel, $callable]) {
            if ($channel == $subscription_channel) {
                $callable($event);
            }
        }
    }
}
?>
