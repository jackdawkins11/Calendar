
<?php

require "config.php";

function loadEvents( $username, $dbusername, $dbpassword, $dsn ){
    try{
        $connection = new PDO( $dsn, $dbusername, $dbpassword );
        $query = $connection->prepare( "SELECT * FROM events WHERE username=?" );
        $query->execute( array( $username ) );
    }catch( PDOException $e ){
        $response->events = array();
        return $response;
    }
    if( $events = $query->fetchAll() ){
        $response->events = array();
        for( $i=0; $i<sizeof( $events ); $i++ ){
            $event->username = $events[ $i ][ 0 ];
            $event->title = $events[ $i ][ 1 ];
            $event->description = $events[ $i ][ 2 ];
            $event->startTime = $events[ $i ][ 3 ];
            $event->endTime = $events[ $i ][ 4 ];
            array_push( $response->events, clone($event) );
        }
        return $response;
    }else{
        $response->events = array();
        return $response;
    }
}

$response = loadEvents( $_POST[ 'username' ],
    $dbusername, $dbpassword, $dsn );
echo json_encode( $response );

?>
