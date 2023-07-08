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
            this.cardwidth = 100;
            this.cardheight = 150;
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

            // TODO: Setting up players boards if needed

            this.createShips(gamedatas);
            this.moveShips(gamedatas);
            
            this.createAndFillHands(gamedatas);

            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            dojo.connect( this.hand, 'onChangeSelection', this, 'onMyHandSelectionChanged' );
            dojo.connect( this.sideboard, 'onChangeSelection', this, 'onExtraHandSelectionChanged' );
            
            this.setSelectableFields(this.gamedatas.selectableFields);

            console.log( "Ending game setup" );
        },
        createShips: function( gamedatas ) {
            for( var player_id in gamedatas.playersIncludingRobots ) {
                var player = gamedatas.playersIncludingRobots[player_id];
                this.addTokenOnBoard(player.id, player.no, player.color, 'ocean', 0);
            }            
        },
        createAndFillHands: function( gamedatas ) {
            this.hand = this.createAndFillHand('hand', this.gamedatas.hand);
            this.sideboard = this.createAndFillHand('sideboard', this.gamedatas.sideboard);
            this.selectedhand = this.createAndFillHand('selectedhand', this.gamedatas.selectedhand);
            this.playedhand = this.createAndFillHand('playedhand', this.gamedatas.playedhand);
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
        onMyHandSelectionChanged: function() {
            var items = this.hand.getSelectedItems();

            if (items.length > 0) {
                var card_id = items[0].id;
                this.selectCard(card_id);
            }
            this.hand.unselectAll();
        },
        onExtraHandSelectionChanged: function() {
            var items = this.sideboard.getSelectedItems();

            if (items.length > 0) {
                var card_id = items[0].id;
                this.selectExtraCard(card_id);
            }
            this.sideboard.unselectAll();
        },
        onSelectField: function( evt ) {
            dojo.stopEvent( evt );
            var elements = evt.currentTarget.id.split('_');
            console.log("Category" + elements[1]);
            console.log("ID" + elements[2]);
            this.selectField(evt.currentTarget.id);
        },

        ///////////////////////////////////////////////////
        //// Utility methods
        
        addTokenOnBoard: function(player_id, number, color, category, id) {
            console.log("addTokenOnBoard "+player_id+" "+number+" "+color+" "+category+" "+id);
            dojo.place( this.format_block( 'jstpl_token0', {
                player: player_id,
                player_number: number - 1,
                color: color,
                nr: id
            } ) , 'tokens' );
            
            this.placePlayerBoardAndSlide(player_id, 'token_'+player_id+'_'+id, 'field_'+category+'_'+ 0 );
        },
        placePlayerBoardAndSlide(player_id, token_id, destination) {
            if (player_id > 9) {
                this.placeOnObject(token_id, 'overall_player_board_'+player_id);
            }
            this.slideToObject(token_id, destination).play();
        },
        moveShips: function(gamedatas) {
            for( var player_id in gamedatas.playersIncludingRobots ) {
                var player = gamedatas.playersIncludingRobots[player_id];
                console.log('moveShips player_id ' + player_id + ' ocean_position ' + player.ocean_position);
                this.slideToObject( 'token_'+player_id+'_0', 'field_ocean_'+player.ocean_position).play();
            }
        },
        createAndFillHand: function(name, cards) {
            return this.fillHand(this.createHand(name), cards);
        },
        createHand: function(name) {
            hand = new ebg.stock(); // new stock object for hand
            hand.create( this, $(name), this.cardwidth, this.cardheight );
            hand.image_items_per_row = 9; // 9 images per row
            hand.onItemCreate = dojo.hitch( this, 'setupNewCard' ); 

            // Create cards types:
            for (var type = 0; type < 110; type++) {
                if (type != 35) {
                    hand.addItemType(type, type, g_gamethemeurl + 'img/alle_kaarten.png', type);
                }
            }

            return hand;
        },
        setupNewCard: function( card_div, card_type_id, card_id )
        {
           // Add a special tooltip on the card:
           this.addTooltip(card_div.id, "" + this.gamedatas.tooltipsCards[card_type_id]);
        },
        fillHand: function(hand, cards) {
            hand.removeAll();

            for ( var i in cards) {
                var card = cards[i];
                hand.addToStockWithId(card.type, card.id);
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
        selectExtraCard: function(card_id) {
            if (this.checkAction('selectExtraCard')) {
                console.log("on selectExtraCard "+card_id);

                this.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + 'selectExtraCard' + ".html", {
                    card_id : card_id,
                    lock : true
                }, this, function(result) {
                }, function(is_error) {
                });
            } else {
                console.log("not allowed selectExtraCard "+card_id);
            }
        },
        selectField: function(field_id) {
            if (this.checkAction('selectField')) {
                console.log("on selectField "+field_id);

                this.ajaxcall("/" + this.game_name + "/" + this.game_name + "/" + 'selectField' + ".html", {
                    field_id : field_id,
                    lock : true
                }, this, function(result) {
                }, function(is_error) {
                });
            } else {
                console.log("not allowed selectField "+field_id);
            }
        },
            
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your millefiori.game.php file.
        
        */
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
            dojo.subscribe( 'newPlayerHand', this, "notif_newPlayerHand" );
            this.notifqueue.setSynchronous( 'newPlayerHand', 500 );  

            dojo.subscribe( 'playerHands', this, "notif_playerHands" );
            this.notifqueue.setSynchronous( 'playerHands', 500 );  

            dojo.subscribe( 'selectableFields', this, "notify_selectableFields" );
            this.notifqueue.setSynchronous( 'selectableFields', 500 );

            dojo.subscribe( 'shipMoved', this, "notify_shipMoved" );
            this.notifqueue.setSynchronous( 'shipMoved', 500 );

            dojo.subscribe( 'newScore', this, "notify_newScore" );
            this.notifqueue.setSynchronous( 'newScore', 500 );

            dojo.subscribe( 'cardMoved', this, "notify_cardMoved" );
            this.notifqueue.setSynchronous('cardMoved', 1100);

            dojo.subscribe( 'stockToStock', this, "notify_stockToStock" );
            this.notifqueue.setSynchronous('stockToStock', 1100);

            dojo.subscribe( 'emptyPlayedHand', this, "notify_emptyPlayedHand" );
            this.notifqueue.setSynchronous('emptyPlayedHand', 1100);
        }, 
        notif_newPlayerHand: function(notif) {
            console.log('notif_newPlayerHand');
            this.fillHand(this.hand, notif.args.hand);
        },
        notify_newScore : function(notif) {
            // Update players' score
            console.log('notify_newScore');

            this.scoreCtrl[notif.args.player_id].toValue(notif.args.newScore);
        },
        notif_playerHands: function(notif) {
            console.log('notif_playerHands');
            // Get the color of the player who is returning the discs
            //var targetColor = this.gamedatas.players[ notif.args.player_id ].color;
            if (undefined != notif.args.hand) {
                this.fillHand(this.hand, notif.args.hand);
            }
            if (undefined != notif.args.selectedhand) {
                this.fillHand(this.selectedhand, notif.args.selectedhand);
            }
            if (undefined != notif.args.playedhand) {
                this.fillHand(this.playedhand, notif.args.playedhand);
            }
        },
        notify_emptyPlayedHand: function(notif) {
            console.log('notify_emptyPlayedHand');

            this.playedhand.removeAll();
        },
        notify_shipMoved: function(notif) {
            console.log('notify_shipMoved ' + notif.args.playersIncludingRobots.length);

            this.moveShips(notif.args);
        },
        getStock: function (id) {
            if (id == 'hand') {
                return this.hand;
            }
            if (id == 'selectedhand') {
                return this.selectedhand;
            }
            if (id == 'playedhand') {
                return this.playedhand;
            }
            if (id == 'sideboard') {
                return this.sideboard;
            }
        },
        notify_cardMoved: function(notif) {
            console.log('notify_cardMoved ' + notif.args.card['type'] + ' ' + notif.args.card['id'] + ' ' + notif.args.fromStock + ' -> ' + notif.args.toStock);

            from = notif.args.fromStock;
            if (notif.args.player_id) {
                if(this.isCurrentPlayerActive()) {
                    from = notif.args.fromStock + '_item_' + notif.args.card['id'];
                } else {
                    from = null;
                }
            }

            if (notif.args.toStock) {
                this.getStock(notif.args.toStock).addToStockWithId(notif.args.card['type'], notif.args.card['id'], from);
            }

            if (notif.args.fromStock) {
                this.getStock(notif.args.fromStock).removeFromStockById(notif.args.card['id']);
            }
        },
        notify_selectableFields: function(notif) {
            console.log('notify_selectableFields');
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
        notify_stockToStock: function(notification) {
            this.getStock(notification.args.to).addToStockWithId(notification.args.item['type'], notification.args.item['id'], notification.args.from + '_item_' + notification.args.item['id']);
            this.getStock(notification.args.from).removeFromStockById(notification.args.item['id']);
        }
   });             
});
