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
 * millefiori.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in millefiori_millefiori.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */
include_once(__DIR__.'/modules/GameView/PageBuilder.php');
include_once(__DIR__.'/modules/GameView/ViewCategories.php');

require_once( APP_BASE_PATH."view/common/game.view.php" );
  
class view_millefiori_millefiori extends game_view
{
    protected function getGameName()
    {
        // Used for translations and stuff. Please do not modify.
        return "millefiori";
    }
    
  	function build_page( $viewArgs )
  	{		
  	    // Get players & players number
        $players = $this->game->loadPlayersBasicInfos();
        $players_nbr = count( $players );
        $page_builder = new NieuwenhovenGames\MilleFiori\PageBuilder();
        $categories = NieuwenhovenGames\MilleFiori\ViewCategories::create(null);
        $page_builder->setPage($this->page)->addFields($categories->generateFields())->generateContent();

        /*********** Place your code below:  ************/


        /*
        
        // Examples: set the value of some element defined in your tpl file like this: {MY_VARIABLE_ELEMENT}

        // Display a specific number / string
        $this->tpl['MY_VARIABLE_ELEMENT'] = $number_to_display;

        // Display a string to be translated in all languages: 
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::_("A string to be translated");

        // Display some HTML content of your own:
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::raw( $some_html_code );
        
        */
        
        /*
        
        // Example: display a specific HTML block for each player in this game.
        // (note: the block is defined in your .tpl file like this:
        //      <!-- BEGIN myblock --> 
        //          ... my HTML code ...
        //      <!-- END myblock --> 
        

        $this->page->begin_block( "millefiori_millefiori", "myblock" );
        foreach( $players as $player )
        {
            $this->page->insert_block( "myblock", array( 
                                                    "PLAYER_NAME" => $player['player_name'],
                                                    "SOME_VARIABLE" => $some_value
                                                    ...
                                                     ) );
        }
        
        */
//        $this->page->begin_block( "millefiori_millefiori", "field" );
        $this->page->insert_block('field', array (
            'CATEGORY' => 'Harbour',
            'ID' => 10,
            'LEFT' => 101,
            'TOP' => 103));



        /*********** Do not change anything below this line  ************/
  	}
}
