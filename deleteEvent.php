
<?php

/*
 * returns a json object with
 * addedEvent=true/false
 */

require "config.php";

function addEvent( $username, $title, $startTime, $endTime,
    $dsn, $dbusername, $dbpassword ){
    try{
        $connection = new PDO( $dsn, $dbusername, $dbpassword );
        $query = $connection->prepare(
            "delete from events where username=? and title=? and starttime=? and endtime=?" );
        $query->execute(
            array($username,$title,$startTime,$endTime)
        );
    }catch( PDOException $e ){
        $response->deletedEvent = false;
        $response->message = "Failed to execute statement";
        return $response;
    }
    if( $query->rowCount() ){
        $response->deletedEvent = true;
        return $response;
    }else{
        $response->deletedEvent = false;
        $response->message = "unknown error";
        return $response;
    }
}

session_start();
if( !isset( $_SESSION[ 'loggedin' ] ) ){
    $response->deletedEvent = false;
    echo json_encode( $response );
}else{
    echo json_encode( addEvent( $_POST[ 'username' ], $_POST[ 'title' ],
        $_POST[ 'startTime' ], $_POST[ 'endTime' ],
        $dsn, $dbusername, $dbpassword ) );
}

?>
