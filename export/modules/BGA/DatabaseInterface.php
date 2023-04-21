<?php
namespace NieuwenhovenGames\BGA;
/**
 *------
 * BGA implementation : © Marcel van Nieuwenhoven marcel.eindhoven@hotmail.com
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 *
 */

interface DatabaseInterface {
    public function query(string $query) : void;
    public function getObject(string $query) : array;
    public function getObjectList(string $query) : array;
    public function getCollection(string $query) : array;
    
    public function trace(string $trace) : void;

    /*
    Protected methods that cannot be called directly

    DbQuery($query);
    getUniqueValueFromDB( $sql );
    getCollectionFromDB( $sql, $bSingleValue=false );
    getNonEmptyCollectionFromDB( $sql );
    getNonEmptyObjectFromDB( $sql );
    getObjectFromDB( $sql );
    getObjectListFromDB( $sql, $bUniqueValue=false );
    getDoubleKeyCollectionFromDB( $sql, $bSingleValue=false );
    DbGetLastId();
    DbAffectedRow();
    escapeStringForDB( $string );
    */
}
?>
