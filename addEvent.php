
<?php

/*
 * returns a json object with
 * addedEvent=true/false
 */

require "config.php";

function addEvent( $username, $title, $description, $startTime, $endTime,
    $dsn, $dbusername, $dbpassword ){
    if( preg_match( "/[^A-Za-z0-9]/", $username )
        || preg_match( "/[^A-Za-z]/", $title )
        || preg_match( "/[^A-Za-z:- ]/", $startTime )
        || preg_match( "/[^A-Za-z:- ]/", $endTime ) ){
        $response->addedEvent = false;
        return $response;
    }
    try{
        $description = $conn->quote( $description );
        $connection = new $PDO( $dsn, $dbusername, $dbpassword );
        $query = $connection->prepare(
            "insert into events (username,title,description,starttime,endtime)"
            . " values ( ?, ?, ?, ?, ? )" );
        $query->execute(
            array($username,$title,$description,$startTime,$endTime)
        );
        $result = $query->fetchAll();
    }catch( PDOException $e ){
        $response->addedEvent = false;
        return $response;
    }
    if( $response->rowCount() ){
        $response->addedEvent = true;
        return $response;
    }else{
        $response->addedEvent = false;
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
