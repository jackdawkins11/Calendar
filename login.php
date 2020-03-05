<?php

/*
 * Takes in $_POST[ 'username' ], $_POST[ 'password' ]
 *
 * if there is an associated account, starts a session
 * with the browser and returns a json object with
 * successfullyLoggedIn=true
 *
 * if not, returns a json object with successfullyLoggedIn=false
 * and errorMessage="Some error message"
 */

require "config.php";

function login( $username, $password, $dsn, $dbusername, $dbpassword ){
    if( preg_match( "/[^A-Za-z0-9]/", $username )
        || preg_match( "/[^A-Za-z0-9]/", $password ) ){
        $ret->successfullyLoggedIn = false;
        $ret->errorMessage = "Invalid username or password";
        return $ret;
    }
    try{
        $connection = new PDO( $dsn, $dbusername, $dbpassword );
        $query = "SELECT * FROM members WHERE username='$username' and password='$password'";
        $result = $connection->query( $query );
    }catch( PDOException $error ){
        $ret->successfullyLoggedIn = false;
        $ret->errorMessage = "Couldn't connect to database";
        return $ret;
    } 
    if( $result->fetch() ){
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $ret->successfullyLoggedIn = true;
        return $ret;
    }else{
        $ret->successfullyLoggedIn = false;
        $ret->errorMessage = "Invalid username or password";
        return $ret;
    }
}

$response = login( $_POST[ 'username' ], $_POST[ 'password' ],
    $dsn, $dbusername, $dbpassword );
echo json_encode( $response );

?>
