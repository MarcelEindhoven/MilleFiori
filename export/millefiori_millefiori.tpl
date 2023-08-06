{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- MilleFiori implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    millefiori_millefiori.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->
<div id="hand_wrap" class="whiteblock" style="display: inline-block">
<div style="display: inline-block">
    <h3>My Hand</h3>
    <div id="hand" class="limited_hand">
    </div>
</div>
<div style="display: inline-block">
    <h3>Selected card</h3>
    <div id="selectedhand" class="single_card_hand">
    </div>
</div>
<div style="display: inline-block">
    <h3>Played card</h3>
    <div id="playedhand" class="single_card_hand">
    </div>
</div>
<div style="display: inline-block">
    <h3>Discard pile</h3>
    <div id="discard" class="limited_hand">
    </div>
</div>
</div>
<div id="hand_wrap" class="whiteblock">
    <h3>Board cards</h3>
    <div id="sideboard">
    </div>
</div>

<div id="board">
    <!-- BEGIN field -->
        <div id="field_{CATEGORY}_{ID}" class="field" style="left: {LEFT}px; top: {TOP}px;"></div>
    <!-- END field -->
    <div id="tokens">
    </div>
</div>
<script type="text/javascript">

// Javascript HTML templates
var jstpl_token0='<div class="token token${player_number}" id="token_${player}_${nr}" style="background-color:#${color}"></div>';

/*
// Example:
var jstpl_some_game_item='<div class="my_game_item" id="my_game_item_${MY_ITEM_ID}"></div>';

*/

</script>  

{OVERALL_GAME_FOOTER}
