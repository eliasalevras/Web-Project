<?php
session_start();

include("database_connection.php");
include("functions.php");

$user = login_session_professor($connection);
$_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
$_SESSION['email'] = $user['Professor_email'] ;

if (isset($_SESSION['role']) && $_SESSION['role'] === "professor") {
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['thesis_under_review'])) {
        $student_am = $_POST['student_am'];
        $professor_id = $user['Professor_User_ID'];
        $sql = "SELECT Thesis_ID FROM thesis WHERE Thesis_Student = ? AND Thesis_Epimelitis = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $student_am, $professor_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row   = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row) {
            $thesis_number = $row['Thesis_ID'];
            $sql2 = "UPDATE thesis SET Thesis_Status = 'under_review'
                     WHERE Thesis_Student = ? AND Thesis_Status = 'active'";
            $stmt2 = mysqli_prepare($connection, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $student_am);
            mysqli_stmt_execute($stmt2);
            $affected = mysqli_stmt_affected_rows($stmt2);
            mysqli_stmt_close($stmt2);

            if ($affected > 0) {
                $sql3 = "INSERT INTO thesis_date (Thesis_ID, Thesis_Date, Thesis_Status)
                         VALUES (?, NOW(), 'under_review')";
                $stmt3 = mysqli_prepare($connection, $sql3);
                mysqli_stmt_bind_param($stmt3, "i", $thesis_number);
                mysqli_stmt_execute($stmt3);
                mysqli_stmt_close($stmt3);
                $message = '<div class="alert alert-success text-center"> Η κατάσταση της διπλωματικής έχει οριστεί σε under_review.div>';

                // echo "Thesis status set to under review.";
            } else {
                $message = "<div class='alert alert-success text-center'> Δεν βρέθηκε ενεργή διπλωματική για τον φοιτητή AM: $student_am! " . mysqli_error($connection) . " </div>";


                // echo "No active thesis found for student AM: " . $student_am;
            }
        } else {
            $message = '<div class="alert alert-danger text-center">Αυτός ο φοιτητής δεν υπάρχει!<div>';

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
    <title>Aλλαγή Κατάστασης σε Περατωμένη</title>
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
        <h2 class="mb-4 text-center text-primary">Aλλαγή Κατάστασης Διπλωματικής Εργασίας σε "Υπο Εξέταση"</h2>
        
        <?php if (!empty($message)) echo $message; ?>

        <form method="POST" action="thesis_status_under_review.php" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="student_am" class="form-label fw-bold">Εισαγωγή Αριθμού Φοιτητή:</label>
                <input type="text"class="form-control" id="student_am" name="student_am"  placeholder="Πληκτρολογήστε τον αριθμό του Φοιτητή..." required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="thesis_under_review" name="thesis_under_review" class="btn btn-primary btn-lg">
                    Aλλαγή Κατάστασης Διπλωματικής Εργασίας σε "Υπο Εξέταση"
                </button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>