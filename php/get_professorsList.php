<?php
header('Content-Type: application/json');
session_start();

include("database_connection.php");

if (!$conn) {
    die(json_encode(["error" => "Database connection failed"])); // Return error if no connection
}

$query = "SELECT Professor_User_ID, CONCAT(Professor_name, ' ', Professor_surname) AS name FROM professor";
$result = $conn->query($query);
if (!$result) {
    die(json_encode(["error" => "Query failed: " . $conn->error])); // Return error if query fails
}
$professors = [];
while ($row = $result->fetch_assoc()) {
    $professors[] = $row;
}
//header("Content-Type: application/json");
echo json_encode($professors);
exit;
?>