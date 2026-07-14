<?php
// FILE: /ss/config/db.php

$conn = mysqli_connect('localhost', 'root', '', 'school');

if ($conn === false) {
    die("FATAL ERROR: Could not connect to database. " . mysqli_connect_error());
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>