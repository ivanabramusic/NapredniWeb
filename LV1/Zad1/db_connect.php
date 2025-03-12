<?php

$host = 'localhost';   
$username = 'root';   
$password = '';        
$dbname = 'radovi';   

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Konekcija na bazu podataka nije uspjela: " . $conn->connect_error);
}
?>
