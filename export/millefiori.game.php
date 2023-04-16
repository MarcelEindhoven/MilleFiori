<?php
 /**
  *------
  * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
  * MilleFiori implementation : © <Your name here> <Your email address here>
  * 
  * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
  * See http://en.boardgamearena.com/#!doc/Studio for more information.
  * -----
  * 
  * millefiori.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );

require_once(__DIR__.'/modules/BGA/DatabaseInterface.php');

include_once(__DIR__.'/modules/Game.php');
include_once(__DIR__.'/modules/Ocean.php');
include_once(__DIR__.'/modules/Categories.php');
include_once(__DIR__.'/modules/Fields.php');
include_once(__DIR__.'/modules/PlayerProperties.php');
include_once(__DIR__.'/modules/CardsHandler.php');
include_once(__DIR__.'/modules/NotifyHandler.php');
include_once(__DIR__.'/modules/GameSetup/GameSetup.php');
include_once(__DIR__.'/modules/GameSetup/CardsSetup.php');

class MilleFiori extends Table
{
	function __construct( )
	{
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();
        
        self::initGameStateLabels( array( 
            //    "my_first_global_variable" => 10,
            //    "my_second_global_variable" => 11,
            //      ...
            //    "my_first_game_variant" => 100,
            //    "my_second_game_variant" => 101,
            //      ...
        ) );        
        $this->cards = self::getNew( "module.common.deck" );
        $this->cards->init( "card" );

        // Limit game for integration testing
        $this->handSize = 2;
	}

    // NieuwenhovenGames\BGA\DatabaseInterface
    public function query(string $query) : void  {
        self::DbQuery($query);
    }
	
    public function getObject(string $query) : array {
        self::trace("getObject {$query}");
        return self::getObjectFromDB($query);
    }

    public function getObjectList(string $query) : array {
        return self::getObjectListFromDB($query);
    }

    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "millefiori";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the game
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        NieuwenhovenGames\MilleFiori\GameSetup::create($this)->setupPlayers($players, $default_colors)->setupBoard();

        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/

        // Init global values with their initial values
        //self::setGameStateInitialValue( 'my_first_global_variable', 0 );
        
        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //self::initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        // TODO: setup the initial game situation here
        // Create cards
        
        $this->cards->createCards($this->game->getCardDefinitions(), 'deck');       

        // Shuffle deck
        $this->cards->shuffle('deck');

        NieuwenhovenGames\MilleFiori\CardsSetup::create($this->cards)->initialiseSideboard(9);

        // Activate first player (which is in general a good idea :) )
        self::trace( "setupNewGame your message here" );

        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    protected function initialiseHelperClassesIfNeeded() {
        if (!property_exists($this, 'ocean')) {
            self::trace( "Initialise helper classes" );

            $this->notifyHandler = NieuwenhovenGames\MilleFiori\NotifyHandler::create($this);
            $this->playerProperties = NieuwenhovenGames\MilleFiori\PlayerProperties::create($this)->setNotifyInterface($this);
            $this->cardsHandler = NieuwenhovenGames\MilleFiori\CardsHandler::create($this->cards)->setNotifyHandler($this->notifyHandler);

            $this->fields = new NieuwenhovenGames\MilleFiori\Fields();
            $this->ocean = NieuwenhovenGames\MilleFiori\Ocean::create($this->playerProperties);
            $this->categories = NieuwenhovenGames\MilleFiori\Categories::create($this->playerProperties);

            $this->game = NieuwenhovenGames\MilleFiori\Game::create($this);
            $this->game->setCards($this->cards);
            $this->game->setCardsHandler($this->cardsHandler);
            $this->game->setPlayerProperties($this->playerProperties);
            $this->game->setNotifyInterface($this);
            $this->game->setOcean($this->ocean);
            $this->game->setCategories($this->categories);
            $this->game->setFields($this->fields);
        }
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas() {
        self::trace( "getAllDatas your message here" );

        $this->initialiseHelperClassesIfNeeded();

        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!

        $result = $this->getHands($current_player_id);

        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $result['players'] = $this->getPlayerData();
        $result['playersIncludingRobots'] = $this->getPlayerDataIncludingRobots();

        $result['selectableFields'] = $this->getSelectableFieldIDs($current_player_id);
        self::trace("selectableFields ". count($result['selectableFields']));
        $result['tooltipsCards'] = $this->game->getTooltips();

        return $result;
    }
    protected function getPlayerData(): array {
        return $this->playerProperties->getPropertiesPlayers();
    }
    protected function getPlayerDataIncludingRobots(): array {
        return $this->playerProperties->getPropertiesPlayersPlusRobots();
    }
    protected function getHands($player_id) {
        $result['hand'] = $this->cards->getCardsInLocation( 'hand', $player_id );
        $result['selectedhand'] = $this->cards->getCardsInLocation( 'selectedhand', $player_id );
        
        // Cards played beside the table
        $result['sideboard'] = $this->cardsHandler->getSideboard();

        $result['playedhand'] = $this->cards->getCardsInLocation( 'playedhand');

        return $result;
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        // TODO: compute and return the game progression

        return 0;
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */

    function isPlayerARobot($player_id) : bool {
        return $this->playerProperties->isPlayerARobot($player_id);
    }

    function notify_playersHands() {
        $players = self::loadPlayersBasicInfos();
        foreach ($players as $player_id => $player) {
            $this->notif_playerHands($player_id);
        }
    }
    function notify_shipMoved() {
        self::trace("notify_shipMoved ". count($this->getPlayerData()));

        $this->notifyAllPlayers('shipMoved', '', ['players' => $this->playerProperties->getPropertiesPlayersPlusRobots()]);
    }
    function notif_playerHands($current_player_id) {
        self::notifyPlayer($current_player_id, 'playerHands', '', $this->getHands($current_player_id));
    }
    function notify_selectableFields() {
        $active_player_id = self::getActivePlayerId();
        self::trace("notify_selectableFields ". $active_player_id);
        self::notifyPlayer($active_player_id, 'selectableFields', '', ['selectableFields' => $this->getSelectableFieldIDs($active_player_id)]);
    }

    function moveFromSelectedToPlayed() {
        $this->initialiseHelperClassesIfNeeded();

        $active_player_id = self::getActivePlayerId();
        $this->cardsHandler->playSelectedCard($active_player_id);
        $selectedCard = $this->cardsHandler->getOnlyCardFromPlayingHand();
        
        self::notifyPlayer($active_player_id, 'selectableFields', '', 
            $this->categories->getSelectableFieldIDs($active_player_id, $selectedCard['type']));
    }

//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 
    
    function selectCard($card_id) {
        self::checkAction("selectCard");

        $this->initialiseHelperClassesIfNeeded();

        $this->game->moveFromHandToSelected($card_id, self::getCurrentPlayerId());

        $this->gamestate->nextState();
    }

    function selectExtraCard($card_id) {
        self::checkAction("selectExtraCard");

        $this->initialiseHelperClassesIfNeeded();

        $this->cardsHandler->selectExtraCard($card_id);

        $this->gamestate->nextState();
    }

    function selectField($field_id) {
        self::checkAction("playCard");
        self::trace("selectField ". $field_id);

        $this->initialiseHelperClassesIfNeeded();

        $active_player_id = self::getActivePlayerId();

        self::notifyPlayer($active_player_id, 'selectableFields', '', []);

        $gained_extra_card = $this->game->processSelectedField($active_player_id, +$this->fields->getID($field_id));

        if ($gained_extra_card) {
            $this->gamestate->nextState("selectExtraCard");
        } else {
            $this->gamestate->nextState("playCard");
        }
    }

    function getSelectableFieldIDs($player_id) {
        $active_player_id = self::getActivePlayerId();
        self::trace("getSelectableFieldIDs active_player_id". $active_player_id);

        if ($player_id != $active_player_id) {
            self::trace("getSelectableFieldIDs player_id ". $player_id);
            return [];
        }
        $cardBeingPlayed = current($this->cards->getCardsInLocation('playedhand'));
        if (!$cardBeingPlayed) {
            self::trace("getSelectableFieldIDs !cardBeingPlayed" );
            return [];
        }
        self::trace("cardBeingPlayed".implode(',', $cardBeingPlayed));
        self::trace("getSelectableFieldIDs ". $cardBeingPlayed['type']);
        $f = $this->categories->getSelectableFieldIDs($active_player_id, $cardBeingPlayed['type']);
        self::trace("getSelectableFieldIDs ". count($f));
        return $f;
    }
    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    function argumentHands() {
        // Return public information only
        // Get some values from the current game situation in database...
    
        $this->initialiseHelperClassesIfNeeded();
        // return values:
        $current_player_id = self::getCurrentPlayerId();
        return array(
            'sideboard' => $this->cardsHandler->getSideboard(),
            'playedhand' => $this->cards->getCardsInLocation('playedhand'),
        );
        return array ();
    }

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */
    
    function stNewHand() {
        self::trace("stNewHand");
        // Deal 5 cards to each players
        $this->game->dealNewHand($this->handSize);

        $this->gamestate->nextState( 'handDealt' );
    }  
    function stSelectCard() {
        self::trace( "stSelectCard" );
        $this->gamestate->setAllPlayersMultiactive();

        $this->game->allRobotsSelectCard();
    }
    function stSelectedCard() {
        self::trace( "stSelectedCard" );
        if ($this->haveAllPlayersSelectedCard()) {
            // Execute this action before changing the game state to prevent parallel actions
            $this->game->allRobotsPlayCard();

            $this->gamestate->nextState('allPlayersReady');
        } else {
            $this->gamestate->nextState('playersStillBusy');
        }
    }
    private function haveAllPlayersSelectedCard() : bool{
        return $this->cardsHandler->getNumberSelectedCards() == 4;
    }
    private function hasAnyPlayerSelectedCard() : bool  {
        return $this->cardsHandler->getNumberSelectedCards() > 0;
    }
    private function numberPlayerHandCard() : int  {
        self::trace( "numberPlayerHandCard total " .  $this->cardsHandler->getNumberPlayerCards());
        self::trace( "numberPlayerHandCard " .  $this->cardsHandler->getNumberPlayerCards() / 4);
        return $this->cardsHandler->getNumberPlayerCards() / 4;
    }
    function stSelectPlayer() {
        self::trace( "stSelectPlayer" );

        if ($this->hasAnyPlayerSelectedCard()) {
            $this->activeNextPlayer();

            $this->moveFromSelectedToPlayed();

            $this->gamestate->nextState('turnBusy');
        } else if ($this->numberPlayerHandCard() > 1) {
            $this->cardsHandler->swapHands(array_keys($this->playerProperties->getPropertiesPlayersPlusRobots()));

            $this->gamestate->nextState('turnEnded');
        } else {
            $this->gamestate->nextState('roundEnded');
        }
    }
    function stPlayCard() {
        self::trace( "stPlayCard" );
        $this->notify_selectableFields();
    }
    function stSelectExtraCard() {
        self::trace( "stSelectExtraCard" );
        // Allow selection of extra card
    }
      

//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */

    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
