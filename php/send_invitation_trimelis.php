<?php
session_start();
include ("database_connection.php");
include("functions.php");
header("Content-Type: application/json");


$user = login_session_student($conn);

if (isset($_SESSION['role']) && ($_SESSION['role'] === "student")){


// Read JSON input
$inputJSON = file_get_contents("php://input");
$data = json_decode($inputJSON, true);

//  Validate input
if ( !isset($data["professors"]) || !is_array($data["professors"])) {
    echo json_encode(["error" => "Invalid input data"]);
    exit;
}

$professors = array_map('intval', $data["professors"]);  //  Convert to integer array
$studentnum = $user['Student_number'];
//query for thesis_ID
$stmt  = $conn->prepare("SELECT Thesis_ID FROM Thesis WHERE Thesis_Student = ?");

$stmt->bind_param("i", $studentnum);
$stmt->execute();
if (!$stmt->execute()) {
    echo json_encode(["error" => "Failed to insert invitation."]);
    exit;
}
$result = $stmt->get_result();

$thesisId = null;

if ($row = $result->fetch_assoc()) {
    $thesisId = $row['Thesis_ID'];
    
}


if (!$thesisId) {
    echo json_encode(["error" => "No thesis found for student"]);
    exit;
}


 //Insert invitations into the database
$stmt = $conn->prepare("INSERT INTO trimelous_invitation (Thesis_ID, Thesis_Student_Number, Professor_User_ID, Trimelous_Date, Invitation_Status) VALUES (?,?,?, NOW(), 'pending')");
foreach ($professors as $professor_id) {
    $stmt->bind_param("iii",$thesisId, $studentnum, $professor_id);
    $stmt->execute();
}
$stmt->close();
$conn->close();

echo json_encode(["message" => "Invitations sent successfully!"]);
exit; }
else {
    echo json_encode(["error" => "You are not authorized to perform this action"]);
    exit;
}
?>
