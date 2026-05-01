<?php
session_start();

include("database_connection.php");
include("functions.php");

if (isset($_SESSION['role']) && $_SESSION['role'] === "professor") {
    $user = login_session_professor($connection);
    $_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
    $_SESSION['email'] = $user['Professor_email'] ;
    
    $epimelitis = $user['Professor_User_ID'];
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['grading_enabled'])) {
        $student_am = $_POST['student_am'];
        $sql = "SELECT Thesis_ID FROM thesis WHERE Thesis_Student = ? AND Thesis_Epimelitis = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $student_am, $epimelitis);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row   = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row) {
            $thesis_number = $row['Thesis_ID'];
            $sql2 = "INSERT INTO trimelis_vathmologia (Thesis_ID) VALUES (?)";
            $stmt2 = mysqli_prepare($connection, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $thesis_number);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
            $message = '<div class="alert alert-success text-center">Η βαθμολόγηση της διπλωματικής εργασίας είναι ενεργοποιημένη.</div>';
        } else {
           $message = "<div class='alert alert-danger text-center'> Δεν υπάρχει αυτός ο φοιτητής με ΑΜ: $student_am ! " . mysqli_error($connection) . " </div>";

           // echo "This student doesn’t exist!";
        }

        // header("refresh:5; url=./professor_page.php");
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
    <title>Ενεργοποίηση Δυνατότητας Καταχώρησης Βαθμού Διπλωματικής Εργασίας ως Επιβλέπων</title>
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
        <h2 class="mb-4 text-center text-primary">Ενεργοποίηση Δυνατότητας Προσθήκης Βαθμού Διπλωματικής Εργασίας ως Επιβλέπων</h2>
        
        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="grading_enabled.php" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="student_am" class="form-label fw-bold">Εισαγωγή αριθμού φοιτητή για ενεργοποίηση βαθμολόγησης:</label>
                <input type="text"class="form-control" id="student_am" name="student_am"  placeholder="Πληκτρολογήστε τον αριθμό του Φοιτητή..." required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="grading_enabled" name="grading_enabled" class="btn btn-primary btn-lg">
                    Ενεργοποίηση
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>