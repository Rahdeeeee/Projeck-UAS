<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "project_rah";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error){
    echo "KONEKSI ERROR ";
    die("Error!");
}
?>