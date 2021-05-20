<?php

$host         = "localhost";
$username     = "root";
$password     = "";
$dbname       = "hotel";

try {
    $dbconn = new PDO('mysql:host=localhost;dbname=hotel', $username, $password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
