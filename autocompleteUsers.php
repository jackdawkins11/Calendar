<?php

/*
 * Takes in $_POST[ 'startOfName' ]
 *
 * Returns json object with matching=[a,b,...]
 * and each element has
 * username="some username"
 * fullName="some full name"
 */

require "config.php";

function findMatching( $start, $dsn, $dbusername, $dbpassword ){
    if( preg_match( "/[^A-Za-z0-9 ]/", $start ) ){
        $ret->matching = array();
        return $ret;
    }
    $ret->matching = array();
    $fail = false;
    try{
        $connection = new PDO( $dsn, $dbusername, $dbpassword );
        $query = "SELECT username FROM users WHERE username like '$start%'";
        $result = $connection->query( $query );
    }catch( PDOException $error ){
        $fail = true;
    } 
    if( !$fail && $names = $result->fetchAll() ){
        for( $i=0; $i < sizeof( $names ); $i++ ){
            $a->username = $names[ $i ][ 0 ];
            $fail = false;
            try{
                $connection = new PDO( $dsn, $dbusername, $dbpassword );
                $query = "SELECT fullname FROM fullnames WHERE username='$a->username'";
                $result = $connection->query( $query );
            }catch( PDOException $error ){
                $a->fullName = "";
                $fail = true;
            }
            if( !$fail && $names2 = $result->fetchAll() ){
                $a->fullName = $names2[ 0 ][ 0 ];
            }
            array_push( $ret->matching, clone $a );
        }
    }
    $fail = false;
    try{
        $connection = new PDO( $dsn, $dbusername, $dbpassword );
        $query = "SELECT fullname FROM fullnames WHERE fullname like '$start%'";
        $result = $connection->query( $query );
    }catch( PDOException $error ){
        $fail = true;
    } 
    if( !$fail && $names = $result->fetchAll() ){
        for( $i=0; $i < sizeof( $names ); $i++ ){
            $a->fullName = $names[ $i ][ 0 ];
            $fail = false;
            try{
                $connection = new PDO( $dsn, $dbusername, $dbpassword );
                $query = "SELECT username FROM fullnames WHERE fullname='$a->fullName'";
                $result = $connection->query( $query );
            }catch( PDOException $error ){
                $fail = true;
                $a->username = "";
            }
            if( !$fail && $names2 = $result->fetchAll() ){
                $a->username = $names2[ 0 ][ 0 ];
            }
            $found = false;
            for( $r=0; $r < sizeof($ret->matching); $r++){
                if( $ret->matching[ $r ]->username == $a->username ){
                    $found=true;
                    break;
                }
            }
            if( !$found ){
                array_push( $ret->matching, clone $a );
            }
        }
    }
    return $ret;
}

$response = findMatching( $_POST[ 'startOfName' ],
    $dsn, $dbusername, $dbpassword );
echo json_encode( $response );

?>
