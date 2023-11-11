<?php
namespace NieuwenhovenGames\BGA\FrameworkInterfaces;
/**
 * To debug php code you can use some tracing functions available from the parent class such as debug, trace, error, warn, dump.
 * WARNING: tracing does not work in constructor, setupNewGame and game states immediately following starting state (not sure why).
 * 
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

interface Debugging {
    // https://boardgamearena.com/doc/Studio_logs
    public function dump( $name_of_variable, $variable );
    public function debug( $message );
    public function trace(string $trace) : void;
    // https://boardgamearena.com/doc/Studio_logs#BGA_unexpected_exceptions_logs
    public function warn( $message );
    public function error( $message );
}
?>
