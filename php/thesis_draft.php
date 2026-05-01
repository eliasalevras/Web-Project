<?php 
session_start();
include 'database_connection.php';
include 'functions.php';



header('Content-Type: application/json; charset=utf-8');



function json_fail($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}

$user = login_session_student($connection);
if (isset($_SESSION['role']) && ($_SESSION['role'] === "student")) {
   
//$thesis_id = $_POST['Thesis_ID'];
$draft_thesis_PDF = $_FILES['draft_thesis_PDF'];
$thesis_links = $_POST['thesis_links'];
//$flag =0;
// if ( $thesis_links == ''){
//     $flag = 1;
    
// }

if (!isset($_FILES['draft_thesis_PDF']) || $_FILES['draft_thesis_PDF']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['ok' => false, 'error' => 'PDF upload failed']);
    exit;
}

// μόνο PDF
if ($_FILES['draft_thesis_PDF']['type'] !== 'application/pdf') {
    echo json_encode(['ok' => false, 'error' => 'Only PDF files allowed']);
    exit;
}

$upload_dir = '../thesis_draft/';
 if(!is_dir($upload_dir)){
    mkdir($upload_dir, 0755, true); //owner rwx o rx
}
$pdf_name = uniqid('thesis_', true) . ".pdf";
$filepath = $upload_dir . '/' . $pdf_name;

if (move_uploaded_file($_FILES['draft_thesis_PDF']['tmp_name'], $filepath)) {

} else {
    echo json_encode(['ok' => false, 'error' => 'Failed to move uploaded file']);
    exit;
}



$student_number = $user['Student_number'];  
$sql = "SELECT Thesis_ID ,Thesis_Status FROM thesis WHERE Thesis_student = ?";
$stmt = $connection->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $connection->error);
}

$stmt->bind_param(is_numeric($student_number) ? 'i' : 's', $student_number);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
if ($row) {
    $thesis_id = $row['Thesis_ID'];
    $thesis_status = $row['Thesis_Status'];
} else {
    $thesis_id = null;
    echo json_encode(['ok' => false, 'error' => 'No thesis Found!']);
    exit; // or handle "no thesis found"
}
if($thesis_status !== 'under_review'){
    echo json_encode(['ok' => false, 'error' => 'Thesis is not under review yet!']);
    exit; 
}
$stmt->close();
//  $query = "SELECT Thesis_ID FROM thesis WHERE Thesis_student = {$user['Student_number']}";
//  $thesis_id = $connection->query($query)->fetch_assoc()['Thesis_ID'];
 

// $stmt = $connection->prepare("INSERT INTO draft_thesis (Thesis_ID, draft_thesis_PDF, thesis_links) VALUES (?, ?, ?)");
// $stmt->bind_param("iss", $thesis_id, $filepath, $thesis_links);
// $stmt->execute();

if ($stmt = $connection->prepare("INSERT INTO draft_thesis (Thesis_ID, draft_thesis_PDF, LINKS) VALUES (?, ?, ?)")) {
    $stmt->bind_param("iss", $thesis_id, $filepath, $thesis_links);
    if (!$stmt->execute()) {
        json_fail('DB insert failed: ' . $stmt->error, 500);
    }
    $stmt->close();
} else {
    json_fail('Prepare failed: ' . $connection->error, 500);
}

// Success JSON
echo json_encode([
    'ok' => true,
    'message' => 'Draft saved successfully',
    'thesis_id' => $thesis_id,]);}
    

else{ error_log("Unauthorized access attempt by user: " . $user['Student_number']);
    exit;

} 


?>