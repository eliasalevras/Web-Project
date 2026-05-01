<?php 
include 'database_connection.php';
include 'functions.php';
session_start();

header('Content-Type: application/json; charset=utf-8');

$user = login_session_student($connection);
if (isset($_SESSION['role']) && ($_SESSION['role'] === "student")) {
  $nimer_link = $_POST['nimertis_link'];
  $student_num = $user['Student_number'];
  $Thesisnum = $query = "SELECT * FROM thesis WHERE Student_number = $student_num";
    $query = "UPDATE thesis SET Nimertis_link = ? WHERE Thesis_Student = ?";
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);   }else{
    
        $stmt->bind_param("si", $nimer_link, $student_num);
        $stmt->execute();
        // if ($stmt->affected_rows > 0) {
        //     echo json_encode(['ok' => true]);
        // } else {
        //     echo json_encode(['ok' => false, 'error' => 'No rows updated']);
        // }
     }
     $stmt->close();
header("Location: student_page.php");

} else {
    //Redirect to previous page (No access if role is not professor)
    //header('Location: ' . $_SERVER['HTTP_REFERER']);
    // Output JavaScript to redirect back
    echo '<script type="text/javascript">
            alert("You do not have permission to access this page.");
            history.back();
          </script>'; //Δεν θελει κωδικα js σε php tags
    exit;
}










?>