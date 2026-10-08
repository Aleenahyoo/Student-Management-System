<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dashboard";

$conn = mysqli_connect("localhost", "root", "", "dashboard");

if (!$conn) 
{
    die("Connection failed: " . mysqli_connect_error());
}

?>