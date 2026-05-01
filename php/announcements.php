<?php
session_start();

include("database_connection.php");
include("functions.php");

if (isset($_SESSION['role']) && $_SESSION['role'] === "professor") {
    $user = login_session_professor($connection);
    $_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
    $_SESSION['email'] = $user['Professor_email'] ;
    $file = 'announcements_data.json';
    
    $epimelitis = $user['Professor_User_ID'];
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['announcement'])) {
        $student_am = $_POST['student_am'];
        $sql = "SELECT presentation_details.*, thesis.Thesis_Title, 
                CONCAT(student.Student_name,' ',student.Student_surname) as Student,
                CONCAT(professor.Professor_name,' ',professor.Professor_surname) as Professor
                FROM presentation_details
                INNER JOIN thesis ON presentation_details.Thesis_ID = thesis.Thesis_ID
                INNER JOIN student ON thesis.Thesis_Student = student.Student_number
                INNER JOIN professor ON thesis.Thesis_Epimelitis = professor.Professor_User_ID
                 WHERE thesis.Thesis_Student = ? AND thesis.Thesis_Epimelitis = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $student_am, $epimelitis);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row   = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row) {
            $title = $row['Thesis_Title'];
            $student = $row['Student'];
            $professor = $row['Professor'];
            $date = $row['pres_date'];
            $time = $row['pres_time'];
            $type = $row['pres_type'];
            $room = $row['room'];
            $link = $row['pres_link'];

            $announcement_data = [
                "professor" => $professor,
                "student" => $student,
                "title" => $title,
                "date" => $date,
                "time" => $time,
                "type" => $type,
                "room" => $room,
                "link" => $link,
            ];

            //Read existing file or create new array
            if (file_exists($file)) {
                $json = file_get_contents($file);
                $data = json_decode($json, true);
                if (!is_array($data)) {
                    $data = [];
                }
            } else {
                $data = [];
            }

            //Append new data
            $data[] = $announcement_data; 

            //Save back to file
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

            $message = '<div class="alert alert-success text-center">Η ανακοίνωση αποθηκεύτηκε στο json file με επιτυχία</div>';
        } else {
           $message = "<div class='alert alert-danger text-center'> Βεβαιωθείτε ότι είστε ο επιμελητής, ότι ο φοιτητής έχει συμπληρώσει τις σχετικές λεπτομέρειες της παρουσίασης και ότι υπάρχει αυτός ο φοιτητής με ΑΜ: $student_am ! " . mysqli_error($connection) . " </div>"; 
        //Redirect after 5 seconds to the professor page
        header("refresh:5; url=./professor_page.php");
        }
    }
} else {
    echo '<script type="text/javascript">
            alert("You do not have permission to access this page.");
            history.back();
          </script>';
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Δημιουργία Ανακοίνωσης Διπλωματικής Εργασίας ως Επιβλέπων</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="btn btn-outline-light me-2 d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle sidebar">
      ☰ Μενού
    </button>

    <a class="navbar-brand">Η Πλατφόρμα</a>
    <a href="professor_page.php" class="btn btn-success ms-2">Αρχική</a>

            <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger">Αποσύνδεση</a>
        </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <?php
include "sidebar.php";
    ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <main class="col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4 pt-4">
    <div class="card shadow-lg p-4">
        <h2 class="mb-4 text-center text-primary">Δημιουργία Ανακοίνωσης Διπλωματικής Εργασίας ως Επιβλέπων</h2>
        
        <?php if (!empty($message)) echo $message; ?>
    </div>
</div>
</body>
</html>