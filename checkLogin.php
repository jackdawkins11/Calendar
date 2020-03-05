<?php

/*
 * Checks for a session with client
 *
 * if there is, returns json object with
 * isLoggedIn=true and username="some username"
 *
 * if not, returns json object with
 * isLoggedIn=false
 */

session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $responseObj->isLoggedIn = true;
    $responseObj->username = $_SESSION['username'] ;
}else{
    $responseObj->isLoggedin = false;
}
echo json_encode( $responseObj );

?>
