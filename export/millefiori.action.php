<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * millefiori.action.php
 *
 * MilleFiori main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/millefiori/millefiori/myAction.html", ...)
 *
 */
  
  
  class action_millefiori extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "millefiori_millefiori";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
  	// TODO: defines your action entry points there


    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */
    function selectCard() {
      self::setAjaxMode();

      $card_id = self::getArg( "card_id", AT_posint, true );

      $this->game->selectCard($card_id);
      self::ajaxResponse( );
    }

    function selectExtraCard() {
      self::setAjaxMode();

      $card_id = self::getArg( "card_id", AT_posint, true );

      $this->game->selectExtraCard($card_id);
      self::ajaxResponse( );
    }

    function selectField() {
      self::setAjaxMode();

      $field_id = self::getArg( "field_id", AT_alphanum, true );

      $this->game->selectField($field_id);
      self::ajaxResponse( );
    }
  }
  

