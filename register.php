<?php

/*
 * Takes in $_POST[ 'username' ], $_POST[ 'password' ], $_POST[ 'fullName' ]
 *
 * If valid new username and password and fullname, returns
 * json object with successfullyCreatedAccount=true
 *
 * If not returns json object with successfullyCreatedAccount=false
 * and message="what went wrong"
 */

require "config.php";

function createAccount( $username, $fullName, $password,
    $dsn, $dbusername, $dbpassword){
    if( preg_match( '/[^A-Za-z0-9]/', $username ) ){
        $response->successfullyCreatedAccount=false;
        $response->message = "Username must consist of only uppercase letters,"
            . " lowercase letters and numbers.";
        return $response;
    }
    if( preg_match( '/[^A-Za-z ]/', $fullName ) ){
        $response->successfullyCreatedAccount=false;
        $response->message = "Fullname=$fullName must consist of only uppercase letters,"
            . " lowercase letters and spaces.";
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
        $query = "SELECT * FROM users WHERE username='$username'";
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
        $query = "INSERT INTO users (username,password) VALUES ('$username','$password')";
        $result = $connection->query( $query );
    }catch( PDOException $error ){
        $response->successfullyCreatedAccount=false;
        $response->message = "Couldn't connect to database";
        return $response;
    }
    if( 0 >= $result->rowCount() ){
        $response->successfullyCreatedAccount = false;
        $response->message = "Something went wrong";
        return $response;
    }
    try{
        $query = "INSERT INTO fullnames (username,fullname) VALUES ('$username','$fullName')";
        $result = $connection->query( $query );
    }catch( PDOException $error ){
        $response->successfullyCreatedAccount=false;
        $response->message = "Couldn't connect to database";
        return $response;
    }
    if( 0 >= $result->rowCount() ){
        $response->successfullyCreatedAccount = false;
        $response->message = "Something went wrong";
        return $response;
    }
    $response->successfullyCreatedAccount = true;
    $response->message = "Successfully created account";
    return $response;
}

$response = createAccount( $_POST[ 'username' ],
    trim( $_POST[ 'fullName' ] ), $_POST[ 'password' ],
    $dsn, $dbusername, $dbpassword );
echo json_encode( $response );

?>
