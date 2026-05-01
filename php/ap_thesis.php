<?php
session_start();

include("database_connection.php");
include("functions.php");

$user = login_session_secretary($connection);
$_SESSION['email'] = $user['Secretary_name'] . " " . $user['Secretary_surname'];



if (isset($_SESSION['role']) && $_SESSION['role'] === "secretary") {
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['ap_thesis'])) {
        $student_am = $_POST['student_am'];
        $ap_number = $_POST['ap'];
        $sql = "SELECT Thesis_ID FROM thesis WHERE Thesis_Student = ? AND Thesis_Status = 'active'";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $student_am);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row   = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row) {
            $thesis_number = $row['Thesis_ID'];
            $sql2 = "INSERT INTO thesis_ap (Thesis_ID, Arithmos_Pistopoiitikou) VALUES (?, ?)";
            $stmt2 = mysqli_prepare($connection, $sql2);
            mysqli_stmt_bind_param($stmt2, "ii", $thesis_number, $ap_number);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

        //    echo "Arithmos Pistopoiitikou Thesis Submit.";
        
           $message = '<div class="alert alert-success text-center"> Arithmos Pistopoiitikou Thesis Submit!</div>';

        } else {
            $message = '<div class="alert alert-success text-center"> You can only submit ap to active thesis!</div>';

            // echo "You can only submit ap to active thesis!";
        }
        

        header("refresh:5; url=./secretary_page.php");
        // exit;
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
    <title>ΑΠ Καταχώρηση</title>
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
    <a href="secretary_page.php" class="btn btn-success ms-2">Αρχική</a>

            <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger">Αποσύνδεση</a>
        </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <?php
include "secretary_sidebar.php";
    ?>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<main class="col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4 pt-4">
      <div class="card shadow-lg p-4">
        <h2 class="mb-4 text-center text-primary">Καταχώρηση ΑΠ</h2>
        
       <?php
    if (!empty($message)) {
        echo $message;
    }
    ?>

        <form method="POST" action="ap_thesis.php" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="student_am" class="form-label fw-bold">Εισαγωγή Αριθμού Φοιτητή:</label>
                <input type="text"class="form-control" id="student_am" name="student_am"  placeholder="Πληκτρολογήστε τον Αριθμό του Φοιτητή..." required>
            </div>

            <div class="mb-3">
                <label for="ap"  class="form-label fw-bold">Αριθμός Πιστοποιητικού:</label>
                <input type="text" id="ap" class="form-control" name="ap" placeholder="Πληκτρολογήστε το ΑΠ..."  required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="ap_thesis" name="ap_thesis" class="btn btn-primary btn-lg">
                    Καταχώρηση ΑΠ Διπλωματικής Εργασίας
                </button>
            </div>
        </form>
    </div>
</main>
</body>
</html>
