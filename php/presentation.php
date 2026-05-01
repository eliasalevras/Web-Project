<?php 

    session_start();

    include("database_connection.php");
    include("functions.php");

    $user = login_session_student($connection);
    $_SESSION['username'] = $user['Student_name'] . " " . $user['Student_surname'];
    $_SESSION['email'] = $user['Student_email'] ;

if (isset($_SESSION['role']) && ($_SESSION['role'] === "student")){

    $press_date = $_POST['pres_date'];
    $press_time = $_POST['pres_time'];
    //$press_type = $_POST['exam_type'];
    //$pres_link = $_POST['pres_link'];
    //$room = $_POST['room'];
    $press_type = $_POST['exam_type'] ?? null;

    $pres_link = ($press_type === 'online')    ? ($_POST['pres_link'] ?? null) : null;
    $room      = ($press_type === 'in_person') ? ($_POST['room']      ?? null) : null;



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
} else{
    $thesis_id = null;
    echo json_encode(['ok' => false, 'error' => 'No thesis Found!']);
    exit; // or handle "no thesis found"
}
if($thesis_status !== 'under_review'){
    echo json_encode(['ok' => false, 'error' => 'Thesis is not under review yet!']);
    exit; 
}

if ($press_type == "online"){
    $sql = "INSERT INTO presentation_details (Thesis_ID, pres_date, pres_time, pres_type,room, pres_link) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("isssss", $thesis_id, $press_date, $press_time, $press_type, $room, $pres_link);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'error' => 'Failed to schedule presentation']);
    }
}else if ($press_type == "in_person"){
    $sql = "INSERT INTO presentation_details (Thesis_ID, pres_date, pres_time, pres_type, room, pres_link) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }

    $stmt->bind_param("isssss", $thesis_id, $press_date, $press_time, $press_type, $room, $pres_link);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'error' => 'Failed to schedule presentation']);
    }
}else { echo json_encode(['ok' => false, 'error' => 'something went wrong with the presentation type ']);
    exit; }




}else{ error_log("Unauthorized access attempt by user: " . $user['Student_number']);
    exit;

} 



?>