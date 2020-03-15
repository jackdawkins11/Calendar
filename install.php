
<?php

require "config.php";

try{

    $connection = new PDO( "mysql:host=$dbhost", $dbusername, $dbpassword );

    $query = "create database $dbname;"
        . " use $dbname;"
        . " create table users ( username varchar(100) not null, password varchar(100) not null);"
        . " create table fullnames ( username varchar(100) not null, fullname varchar(100) not null);"
        . " create table events ( username varchar(100) not null, title varchar(100),"
        . " description varchar(200), starttime datetime, endtime datetime );";

    $connection->exec( $query );

    echo "Created the database";

}catch( PDOException $e ){
    echo "Error installing database: " . $e->getMessage();
}
