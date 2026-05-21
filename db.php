<?php
$conn = new mysqli("localhost","root","","supershop1_db");

if($conn->connect_error){
    die("DB Failed");
}
?>