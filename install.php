
<?php

require "config.php";

try{

    $connection = new $PDO( "mysql:host=$host", $dbusername, $dbpassword );

    $query = "create database $dbname;"
        . " use $dbname;"
        . " create table users ( username varchar(100) not null, password varchar(100) not null);"
        . " create table fullnames ( username varchar(100) not null, fullname varchar(100) not null);"
        . " create table events ( username varchar(100) not null, title varchar(100) not null,"
        . " description varchar(200), datetime starttime, datetime endtime );";

    $connection->exec( $query );

}catch( PDOException $e ){
    echo "Error installing database: " . $e->getMessage();
}
