<?php

/*
 * Checks for a session with client
 *
 * if there is, returns json object with
 *  isLoggedIn=true
 *  username="some username"
 *  fullName="some full name"
 *
 * if not, returns json object with
 * isLoggedIn=false
 */

require "config.php";

session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $response->isLoggedIn = true;
    $response->username = $_SESSION['username'];
    try{
        $connection = new PDO( $dsn, $dbusername, $dbpassword );
        $query = "SELECT fullname FROM fullnames WHERE username='$response->username'";
        $result = $connection->query( $query );
    }catch( PDOException $error ){
        $response->fullName = "";
        $fail=true;
    } 
    if( !$fail && $names = $result->fetchAll() ){
        $response->fullName = $names[ 0 ][ 0 ];
    }else{
        $response->fullName = "";
    }
}else{
    $response->isLoggedin = false;
}
echo json_encode( $response );

?>
