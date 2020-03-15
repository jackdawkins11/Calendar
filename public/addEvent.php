
<?php

/*
 * returns a json object with
 * addedEvent=true/false
 */

require "../config.php";

function addEvent( $username, $title, $description, $startTime, $endTime,
    $dsn, $dbusername, $dbpassword ){
    try{
        $connection = new PDO( $dsn, $dbusername, $dbpassword );
        $query = $connection->prepare(
            "insert into events (username,title,description,starttime,endtime)"
            . " values ( ?, ?, ?, ?, ? )" );
        $query->execute(
            array($username,$title,$description,$startTime,$endTime)
        );
    }catch( PDOException $e ){
        $response->addedEvent = false;
        $response->message = "Failed to execute statement";
        return $response;
    }
    if( $query->rowCount() ){
        $response->addedEvent = true;
        return $response;
    }else{
        $response->addedEvent = false;
        $response->message = "unknown error";
        return $response;
    }
}

session_start();
if( !isset( $_SESSION[ 'loggedin' ] ) ){
    $response->addedEvent = false;
    echo json_encode( $response );
}else{
    echo json_encode( addEvent( $_POST[ 'username' ], $_POST[ 'title' ],
        $_POST[ 'description' ], $_POST[ 'startTime' ], $_POST[ 'endTime' ],
        $dsn, $dbusername, $dbpassword ) );
}

?>
