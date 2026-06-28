<?php
function db_connect() {
    static $conn;
    if($conn === NULL){
        $conn = new mysqli("localhost","root","","e_pharmacy");
        if($conn->connect_error){
            die("connection failed: " . $conn->connect_error);
        }
    }
    return $conn;
}

    $conn = db_connect();
?>
    