/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * MilleFiori implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * millefiori.js
 *
 * MilleFiori user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    "ebg/stock"
],
function (dojo, declare) {
    return declare("bgagame.millefiori", ebg.core.gamegui, {
        constructor: function(){
            console.log('millefiori constructor');
              
            // Here, you can init the global variables of your user interface
            // Example:
            // this.myGlobalValue = 0;
            this.cardwidth = 72;
            this.cardheight = 96;
        },
        
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
            console.log( "Starting game setup" );
            
            // Setting up player boards
            for( var player_id in gamedatas.players ) {
                var player = gamedatas.players[player_id];
                this.addTokenOnBoard(player_id, 0, 'Ocean', 0);
                         
                // TODO: Setting up players boards if needed
            }
            this.moveShips();
            
            // TODO: Set up your game interface here, according to "gamedatas"
            // Player hand
            this.myhand = this.createAndFillHand('myhand', this.gamedatas.myhand);
            
            this.boardHand = this.createAndFillHand('boardhand', this.gamedatas.boardhand);

            this.selectedhand = this.createAndFillHand('selectedhand', this.gamedatas.selectedhand);
            this.playedhand = this.createAndFillHand('playedhand', this.gamedatas.playedhand);

            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            dojo.connect( this.myhand, 'onChangeSelection', this, 'onMyHandSelectionChanged' );
            

            console.log( "Ending game setup" );
        },

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
            console.log( 'Entering state: '+stateName );
            
            switch( stateName )
            {
            case "selectedCard":
                console.log( "Set hands " +  args.args.myhand.length + ", " + args.args.selectedhand.length);
                this.fillHand(this.myhand, args.args.myhand);
                this.fillHand(this.selectedhand, args.args.selectedhand);
                break;
            case "playCard":
                console.log( "Set hands " +  args.args.myhand.length + ", " + args.args.selectedhand.length);
                this.fillHand(this.myhand, args.args.myhand);
                this.fillHand(this.selectedhand, args.args.selectedhand);
                this.fillHand(this.playedhand, args.args.playedhand);
                break;
                /* Example:
            
            case 'myGameState':
            
                // Show some HTML block at this game state
                dojo.style( 'my_html_block_id', 'display', 'block' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
            
            switch( stateName )
            {
            
            /* Example:
            
            case 'myGameState':
            
                // Hide the HTML block we are displaying only during this game state
                dojo.style( 'my_html_block_id', 'display', 'none' );
                
                break;
           */
           
           
            case 'dummmy':
                break;
            }               
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
            console.log( 'onUpdateActionButtons: '+stateName );
                      
            if( this.isCurrentPlayerActive() )
            {           
                this.moveShips();
 
                switch( stateName )
                {
/*               
                 Example:
 
                 case 'myGameState':
                    
                    // Add 3 action buttons in the action status bar:
                    
                    this.addActionButton( 'button_1_id', _('Button 1 label'), 'onMyMethodToCall1' ); 
                    this.addActionButton( 'button_2_id', _('Button 2 label'), 'onMyMethodToCall2' ); 
                    this.addActionButton( 'button_3_id', _('Button 3 label'), 'onMyMethodToCall3' ); 
                    break;
*/
                }
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        // Get card unique identifier based on its color and value
        getCardUniqueId : function(color, value) {
            return (color - 0) * 13 + (value - 0);
        },
        addTokenOnBoard: function(player_id, nr, category, id) {
            dojo.place( this.format_block( 'jstpl_token0', {
                player: player_id,
                player_number: this.gamedatas.players[ player_id ].player_number - 1,
                color: this.gamedatas.players[ player_id ].color,
                nr: nr
            } ) , 'tokens' );
            
            this.placeOnObject( 'token_'+player_id+'_'+nr, 'overall_player_board_'+player_id );
            this.slideToObject( 'token_'+player_id+'_'+nr, 'field_'+category+'_'+id ).play();
        },
        moveShips: function() {
            for( var player_id in this.gamedatas.players ) {
                var player = this.gamedatas.players[player_id];
                this.slideToObject( 'token_'+player_id+'_0', 'field_Ocean_'+player.ocean_position).play();
            }
        },
        onMyHandSelectionChanged: function() {
            var items = this.myhand.getSelectedItems();

            if (items.length > 0) {
                var card_id = items[0].id;
                this.selectCard(card_id);
            }
            this.myhand.unselectAll();
        },
        selectCard: function(card_id) {
            if (this.checkAction('selectCard')) {
                console.log("on selectCard "+card_id);

                this.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + 'selectCard' + ".html", {
                    card_id : card_id,
                    lock : true
                }, this, function(result) {
                }, function(is_error) {
                });
            } else {
                console.log("not allowed selectCard "+card_id);
            }
        },
        createAndFillHand: function(name, cards) {
            return this.fillHand(this.createHand(name), cards);
        },
        createHand: function(name) {
            myhand = new ebg.stock(); // new stock object for hand
            myhand.create( this, $(name), this.cardwidth, this.cardheight );
            myhand.image_items_per_row = 13; // 13 images per row

            // Create cards types:
            for (var color = 0; color <= 3; color++) {
                for (var value = 0; value <= 12; value++) {
                    // Build card type id
                    var card_type_id = this.getCardUniqueId(color, value);
                    myhand.addItemType(card_type_id, card_type_id, g_gamethemeurl + 'img/cards.jpg', card_type_id);
                }
            }

            return myhand;
        },
        fillHand: function(hand, cards) {
            hand.removeAll();

            for ( var i in cards) {
                var card = cards[i];
                var color = card.type;
                var value = card.type_arg;
                hand.addToStockWithId(this.getCardUniqueId(color, value), card.id);
            }

            return hand;
        },

        ///////////////////////////////////////////////////
        //// Player's action
        
        /*
        
            Here, you are defining methods to handle player's action (ex: results of mouse click on 
            game objects).
            
            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server
        
        */
        
        /* Example:
        
        onMyMethodToCall1: function( evt )
        {
            console.log( 'onMyMethodToCall1' );
            
            // Preventing default browser reaction
            dojo.stopEvent( evt );

            // Check that this action is possible (see "possibleactions" in states.inc.php)
            if( ! this.checkAction( 'myAction' ) )
            {   return; }

            this.ajaxcall( "/millefiori/millefiori/myAction.html", { 
                                                                    lock: true, 
                                                                    myArgument1: arg1, 
                                                                    myArgument2: arg2,
                                                                    ...
                                                                 }, 
                         this, function( result ) {
                            
                            // What to do after the server call if it succeeded
                            // (most of the time: nothing)
                            
                         }, function( is_error) {

                            // What to do after the server call in anyway (success or failure)
                            // (most of the time: nothing)

                         } );        
        },        
        
        */

        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your millefiori.game.php file.
        
        */
        setupNotifications: function()
        {
            console.log( 'notifications subscriptions setup' );
            
            // TODO: here, associate your game notifications with local methods
            
            // Example 1: standard notification handling
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            
            // Example 2: standard notification handling + tell the user interface to wait
            //            during 3 seconds after calling the method in order to let the players
            //            see what is happening in the game.
            // dojo.subscribe( 'cardPlayed', this, "notif_cardPlayed" );
            // this.notifqueue.setSynchronous( 'cardPlayed', 3000 );
            // 
        },  
        
        // TODO: from this point and below, you can write your game notifications handling methods
        
        /*
        Example:
        
        notif_cardPlayed: function( notif )
        {
            console.log( 'notif_cardPlayed' );
            console.log( notif );
            
            // Note: notif.args contains the arguments specified during you "notifyAllPlayers" / "notifyPlayer" PHP call
            
            // TODO: play the card in the user interface.
        },    
        
        */
   });             
});
