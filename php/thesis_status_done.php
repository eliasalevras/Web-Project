<?php
session_start();

include("database_connection.php");
include("functions.php");

$user = login_session_secretary($connection);
$_SESSION['email'] = $user['Secretary_name'] . " " . $user['Secretary_surname'];

//Apla na prostheso if gia link nimerti kai kataxorisi vathmou otan ta prosthesoume stin vasi
if (isset($_SESSION['role']) && $_SESSION['role'] === "secretary") {
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['thesis_done'])) {
        $student_am = $_POST['student_am'];
        $sql = "SELECT thesis.Thesis_ID
        FROM thesis 
        INNER JOIN trimelis_vathmologia ON thesis.Thesis_ID = trimelis_vathmologia.Thesis_ID
        WHERE thesis.Thesis_Student = ? AND thesis.Nimertis_link IS NOT NULL AND trimelis_vathmologia.Trimelis_Final_Grade IS NOT NULL";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $student_am);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row   = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row) {
            $thesis_number = $row['Thesis_ID'];
             $sql2 = "UPDATE thesis INNER JOIN trimelis_vathmologia as tv ON thesis.Thesis_ID = tv.Thesis_ID 
                     SET thesis.Thesis_Status = 'ready', thesis.Thesis_Final_Grade = tv.Trimelis_Final_Grade
                     WHERE thesis.Thesis_Student = ? AND thesis.Thesis_Status = 'under_review' AND tv.Trimelis_Final_Grade IS NOT NULL";
            $stmt2 = mysqli_prepare($connection, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $student_am);
            mysqli_stmt_execute($stmt2);
            $affected = mysqli_stmt_affected_rows($stmt2);
            mysqli_stmt_close($stmt2);

            if ($affected > 0) {
                $sql3 = "INSERT INTO thesis_date (Thesis_ID, Thesis_Date, Thesis_Status)
                         VALUES (?, NOW(), 'ready')";
                $stmt3 = mysqli_prepare($connection, $sql3);
                mysqli_stmt_bind_param($stmt3, "i", $thesis_number);
                mysqli_stmt_execute($stmt3);
                mysqli_stmt_close($stmt3);
                $message = '<div class="alert alert-success text-center"> Thesis status set to ready and total_grade added.</div>';

                // echo "Thesis status set to ready.";
            } else {

                 $message = '<div class="alert alert-danger text-center"> Grading not completed yet or no under_review thesis found for student AM:'. $student_am .'</div>';

                // echo "No under_review thesis found for student AM: " . $student_am;
            }
        } else {
            $message = '<div class="alert alert-danger text-center">Ο φοιτητής δεν υπάρχει ή δεν έχει αναρτηθεί ο σύνδεσμος προς το Νημερτή</div>';

            // echo "This student doesn’t exist!";
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
    <title>Aλλαγή Κατάστασης</title>
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
        <h2 class="mb-4 text-center text-primary">Aλλαγή Κατάστασης Διπλωματικής Εργασίας σε Περατωμένη</h2>
        
        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="thesis_status_done.php" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="student_am" class="form-label fw-bold">Εισαγωγή Αριθμού Φοιτητή:</label>
                <input type="text"class="form-control" id="student_am" name="student_am"  placeholder="Πληκτρολογήστε τον αριθμό του Φοιτητή..." required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="thesis_done" name="thesis_done" class="btn btn-primary btn-lg">
                    Αλλαγή Κατάστασης Διπλωματικής Εργασίας σε Περατωμένη
                </button>
            </div>
        </form>
</main>
</div>
</body>
</html>
