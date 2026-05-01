<?php
session_start();

include("database_connection.php");
include("functions.php");

if (isset($_SESSION['role']) && $_SESSION['role'] === "professor") {
    $user = login_session_professor($connection);
    $_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
    $_SESSION['email'] = $user['Professor_email'] ;
    
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['professor_notes'])) {
        $student_am = $_POST['student_am'];
        $notes = $_POST['notes'];
        $professor_id = $user['Professor_User_ID'];
        $sql = "SELECT thesis.Thesis_ID FROM thesis JOIN trimelis ON thesis.Thesis_ID = trimelis.Thesis_ID 
        WHERE thesis.Thesis_Student = ? AND (trimelis.Trimelis_Professor_1 = ? OR trimelis.Trimelis_Professor_2 = ? OR trimelis.Trimelis_Professor_3 = ?)";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "iiii", $student_am, $professor_id, $professor_id, $professor_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row   = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row) {
            $thesis_number = $row['Thesis_ID'];
            $sql2 = "INSERT INTO Thesis_Professor_Notes (Thesis_ID, Professor_User_ID, Notes)
                     VALUES (?, ?, ?)";
            $stmt2 = mysqli_prepare($connection, $sql2);
            mysqli_stmt_bind_param($stmt2, "iis", $thesis_number, $professor_id, $notes);
            mysqli_stmt_execute($stmt2);
            $affected = mysqli_stmt_affected_rows($stmt2);
            mysqli_stmt_close($stmt2);
            $message = '<div class="alert alert-success text-center"> Οι σημειώσεις έχουν προστεθεί επιτυχώς.</div>';
        } else {
            $message = '<div class="alert alert-danger  text-center"> Αυτός ο φοιτητής δεν υπάρχει ή δεν είστε μέλος τριμελούς επιτροπής της συγκεκριμένης διπλωματικής εργασίας.</div>';

            // echo "This student doesn’t exist!";
        }

        // header("refresh:5; url=./professor_page.php");
        // exit;
    }
}

else {
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
    <title>Σημειώσεις Διπλωματικής Εργασίας</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-custom {
            max-width: 800px;
            margin-top: 50px;
        }
    </style>
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
        <h2 class="mb-4 text-center text-primary">Σημειώσεις Διπλωματικής Εργασίας</h2>
        
       <?php
    if (!empty($message)) {
        echo $message;
    }
    ?>

        <form method="POST" action="professor_notes.php" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="student_am" class="form-label fw-bold">Εισαγωγή Αριθμού Φοιτητή που επιθυμείτε να προσθέσετε σημειώσεις για την Διπλωματική Εργασία:</label>
                <input type="text"class="form-control" id="student_am" name="student_am"  placeholder="Πληκτρολογήστε τον Αριθμό του Φοιτητή..." required>
            </div>

            <div class="mb-3">
                <label for="notes"  class="form-label fw-bold">Σημειώσεις:</label>
                <input type="text" id="notes" class="form-control" name="notes" placeholder="Σημειώσεις..."  required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="professor_notes" name="professor_notes" class="btn btn-primary btn-lg">
                    Προσθήκη Σημειώσεων Διπλωματικής Εργασίας
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>