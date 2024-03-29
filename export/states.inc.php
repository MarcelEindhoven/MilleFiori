<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 * 
 * states.inc.php
 *
 * MilleFiori game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!

 
$machinestates = array(

    // The initial state. Please do not modify.
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => array( "" => 5 )
    ),
    
    // Note: ID=2 => your first state


    5 => array(
        "name" => "newHand",
        "description" => '',
        "type" => "game",
        "action" => "stNewHand",
        "updateGameProgression" => true,   
        "transitions" => array("selectCardSingleActivePlayer" => 20, "selectCardMultipleActivePlayers" => 10)
    ),
    10 => array(
        "name" => "allRobotsSelectCard",
        "description" => clienttranslate('Robots select a card'),
        "descriptionmyturn" => clienttranslate('Robots select a card'),
        "type" => "game",
        "action" => "stRobotsSelectCard",
        "transitions" => array("" => 12)
    ),
    12 => array(
        "name" => "selectCardMultipleActivePlayers",
        "description" => clienttranslate('everyone must select a card'),
        "descriptionmyturn" => clienttranslate('everyone must select a card'),
        "type" => "multipleactiveplayer",
        "action" => "stSelectCardMultipleActivePlayers",
        "possibleactions" => array( "selectCard" ),
        "transitions" => array( "" => 13),
    ),

    13 => array(
        "name" => "selectedCard",
        "description" => clienttranslate('selected a card'),
        "descriptionmyturn" => clienttranslate('selected a card'),
        "type" => "game",
        "action" => "stSelectedCard",
        "transitions" => array( "playersStillSelectingCard" => 12, "allPlayersSelectedCard" => 20 )
    ),
    20 => array(
        "name" => "activatePlayerOrRobot",
        "description" => clienttranslate('Who will play next'),
        "descriptionmyturn" => clienttranslate('Who will play next'),
        "type" => "game",
        "action" => "stActivatePlayerOrRobot",
        "transitions" => array( "activatePlayerToSelectCard" => 30, "activatePlayerToPlayCard" => 31, "activateRobotToSelectCard" => 40, "activateRobotToPlayCard" => 41 )
    ),
    22 => array(
        "name" => "endOfPlayerTurn",
        "description" => clienttranslate('Selecting next player'),
        "descriptionmyturn" => clienttranslate('Selecting next player'),
        "type" => "game",
        "action" => "stEndOfTurn",
        "transitions" => array( "turnEnded" => 20, "roundEnded" => 24)
    ),
    24 => array(
        "name" => "endOfRound",
        "description" => clienttranslate('End of round'),
        "descriptionmyturn" => clienttranslate('End of round'),
        "type" => "game",
        "action" => "stEndOfRound",
        "transitions" => array( "roundEndedSingleActivePlayer" => 12, "roundEndedMultipleActivePlayers" => 20, "handEnded" => 5, "gameEnded" => 99 )
    ),
    30 => array(
        "name" => "selectCardSingleActivePlayer",
        "description" => clienttranslate('${actplayer} must select a card'),
        "descriptionmyturn" => clienttranslate('${you} must select a card'),
        "type" => "activeplayer",
        "action" => "stPlayerSelectsCard",
        "possibleactions" => array( "selectCard" ),
        "transitions" => array( "" => 31),
    ),
    31 => array(
        "name" => "playCard",
        "description" => clienttranslate('${actplayer} must play the card'),
        "descriptionmyturn" => clienttranslate('${you} must play the card'),
        "type" => "activeplayer",
        "action" => "stPlayerPlaysCard",
        "possibleactions" => array( "selectField"),
        "transitions" => array( "turnEnded" => 22, "selectExtraCard" => 32 )
    ),
    32 => array(
        "name" => "selectExtraCard",
        "description" => clienttranslate('${actplayer} must select an extra card'),
        "descriptionmyturn" => clienttranslate('${you} must select an extra card'),
        "type" => "activeplayer",
        "action" => "stPlayerSelectsExtraCard",
        "possibleactions" => array( "selectExtraCard" ),
        "transitions" => array( "selectExtraCard" => 31 )
    ),
    40 => array(
        "name" => "robotSelectsCard",
        "description" => clienttranslate('Robot selects card'),
        "descriptionmyturn" => clienttranslate('Robot selects card'),
        "type" => "game",
        "action" => "stRobotSelectsCard",
        "transitions" => array("" => 41)
    ),
    41 => array(
        "name" => "robotPlaysCard",
        "description" => clienttranslate('Robot plays card'),
        "descriptionmyturn" => clienttranslate('Robot plays card'),
        "type" => "game",
        "action" => "stRobotPlaysCardSelectsField",
        "transitions" => array("selectExtraCard" => 42, "turnEnded" => 22)
    ),
    42 => array(
        "name" => "robotSelectsExtraCard",
        "description" => clienttranslate('Robot selects extra card'),
        "descriptionmyturn" => clienttranslate('Robot selects extra card'),
        "type" => "game",
        "action" => "stRobotSelectsExtraCard",
        "transitions" => array("playCard" => 41)
    ),
   
    // Final state.
    // Please do not modify (and do not overload action/args methods).
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )

);



