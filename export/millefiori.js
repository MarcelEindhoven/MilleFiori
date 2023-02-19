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
                this.addTokenOnBoard(player_id, 0, 'ocean', 0);
                         
                // TODO: Setting up players boards if needed
            }
            this.moveShips();
            
            // Player hand
            this.myhand = this.createAndFillHand('myhand', this.gamedatas.myhand);
            this.boardHand = this.createAndFillHand('boardhand', this.gamedatas.boardhand);
            this.selectedhand = this.createAndFillHand('selectedhand', this.gamedatas.selectedhand);
            this.playedhand = this.createAndFillHand('playedhand', this.gamedatas.playedhand);

            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            dojo.connect( this.myhand, 'onChangeSelection', this, 'onMyHandSelectionChanged' );
            
            this.setSelectableFields(this.gamedatas.selectableFields);

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
            case "playCard":
                //this.fillHand(this.boardhand, args.args.boardhand);
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
                      
            this.moveShips();
            if( this.isCurrentPlayerActive() )
            {           
                console.log( 'onUpdateActionButtons: isCurrentPlayerActive' );
 
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
            console.log("addTokenOnBoard "+player_id+" "+nr+" "+category+" "+id);
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
                this.slideToObject( 'token_'+player_id+'_0', 'field_ocean_'+player.ocean_position).play();
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
        onSelectField: function( evt ) {
            dojo.stopEvent( evt );
            var elements = evt.currentTarget.id.split('_');
            console.log("Category" + elements[1]);
            console.log("ID" + elements[2]);
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
        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your millefiori.game.php file.
        
        */
        notif_playerHands: function(notif) {
            // Get the color of the player who is returning the discs
            //var targetColor = this.gamedatas.players[ notif.args.player_id ].color;
            this.fillHand(this.myhand, notif.args.myhand);
            //this.fillHand(this.boardhand, notif.args.boardhand);
            this.fillHand(this.selectedhand, notif.args.selectedhand);
            this.fillHand(this.playedhand, notif.args.playedhand);

        },
        notify_selectableFields: function(notif) {
            this.setSelectableFields(notif.args.selectableFields);
        },
        setSelectableFields: function(selectableFields) {
            console.log('selectableFields');
            dojo.query('.selectable').removeClass('selectable');
            for (var i in selectableFields) {
                console.log('selectableField '+ selectableFields[i]);
                dojo.addClass(selectableFields[i], 'selectable');
            }
            dojo.query('.selectable').connect('onclick', this, 'onSelectField');
        },
        setupNotifications: function() {
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
            dojo.subscribe( 'playerHands', this, "notif_playerHands" );
            this.notifqueue.setSynchronous( 'playerHands', 500 );        },  
        
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
