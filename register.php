<?php

/*
 * Takes in $_POST[ 'username' ], $_POST[ 'password' ]
 *
 * If valid new username and password, returns
 * json object with successfullyCreatedAccount=true
 *
 * If not returns json object with successfullyCreatedAccount=false
 * and message="what went wrong"
 */

require "config.php";

function successfullyCreatedAccount( $username, $password,
    $dsn, $dbusername, $dbpassword){
    if( preg_match( '/[^A-Za-z0-9]/', $username ) ){
        $response->successfullyCreatedAccount=false;
        $response->message = "Username must consist of only uppercase letters,"
            . " lowercase letters and numbers.";
        return $response;
    }
    if( preg_match( '/[^A-Za-z0-9]/', $password )
        || strlen( $password ) < 8
        || !preg_match( "#[0-9]+#", $password )
        || !preg_match( "#[a-z]+#", $password )
        || !preg_match( "#[A-Z]+#", $password )  ){
        $response->successfullyCreatedAccount=false;
        $response->message = "Password must contain and only contain uppercase letters,"
            . " lowercase letters and numbers, and must be more than 8 characters long.";
        return $response;
    }
    try{
        $connection = new PDO( $dsn, $dbusername, $dbpassword );
        $query = "SELECT * FROM members WHERE username='$username'";
        $result = $connection->query( $query );
    }catch( PDOException $error ){
        $response->successfullyCreatedAccount=false;
        $response->message = "Couldn't connect to database";
        return $response;
    }
    if( $result->fetch() ){
        $response->successfullyCreatedAccount=false;
        $response->message = "That username is already in use";
        return $response;
    }
    try{
        $query = "INSERT INTO members (username,password) VALUES ('$username','$password')";
        $result = $connection->query( $query );
    }catch( PDOException $error ){
        $response->successfullyCreatedAccount=false;
        $response->message = "Couldn't connect to database";
        return $response;
    }
    if( 0 < $result->rowCount() ){
        $response->successfullyCreatedAccount = true;
        $response->message = "Successfully created account";
        return $response;
    }else{
        $response->successfullyCreatedAccount = false;
        $response->message = "Something went wrong";
        return $response;
    }
}

$response = successfullyCreatedAccount( $_POST[ 'username' ], $_POST[ 'password' ],
    $dsn, $dbusername, $dbpassword );
echo json_encode( $response );

?>
