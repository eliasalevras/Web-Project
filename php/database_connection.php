<?php
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "1234";
    $dbname = "web2425";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    $connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
?>