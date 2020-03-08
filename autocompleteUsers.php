<?php

/*
 * Takes in $_POST[ 'startOfName' ]
 *
 * Returns json object with matching=["option1","option2",...]
 */

require "config.php";

function findMatching( $start, $dsn, $dbusername, $dbpassword ){
    if( preg_match( "/[^A-Za-z0-9]/", $start ) ){
        $ret->matching = array();
        return $ret;
    }
    try{
        $connection = new PDO( $dsn, $dbusername, $dbpassword );
        $query = "SELECT username FROM members WHERE username like '$start%'";
        $result = $connection->query( $query );
    }catch( PDOException $error ){
        $ret->matching = array();
        return $ret;
    } 
    $ret->matching = array();
    if( $names = $result->fetchAll() ){
        for( $i=0; $i < sizeof( $names ); $i++ ){
            array_push( $ret->matching, $names[ $i ][ 0 ] );
        }
    }
    return $ret;
}

$response = findMatching( $_POST[ 'startOfName' ],
    $dsn, $dbusername, $dbpassword );
echo json_encode( $response );

?>
