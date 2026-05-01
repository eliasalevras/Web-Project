<?php
session_start();

include("database_connection.php");
include("functions.php");

if (isset($_SESSION['role']) && $_SESSION['role'] === "professor") {
    $user = login_session_professor($connection);
    $_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
    $_SESSION['email'] = $user['Professor_email'] ;



    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['professor_grading'])) {
        $student_am = $_POST['student_am'];
        $quality_goals = $_POST['quality_goals'];
        $time_interval = $_POST['time_interval'];
        $text_quality = $_POST['text_quality'];
        $presentation = $_POST['presentation'];
        $professor_id = $user['Professor_User_ID'];
        $sql = "SELECT thesis.Thesis_ID FROM thesis JOIN trimelis ON thesis.Thesis_ID = trimelis.Thesis_ID 
        WHERE thesis.Thesis_Student = ? AND thesis.Thesis_Status = 'under_review' AND (trimelis.Trimelis_Professor_1 = ? OR trimelis.Trimelis_Professor_2 = ? OR trimelis.Trimelis_Professor_3 = ?)";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "iiii", $student_am, $professor_id, $professor_id, $professor_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row   = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        $thesis_number = $row['Thesis_ID'];

        $sql_exists = "SELECT Thesis_ID FROM trimelis_vathmologia WHERE Thesis_ID = ?";
        $stmt_exists = mysqli_prepare($connection, $sql_exists);
        mysqli_stmt_bind_param($stmt_exists, "i", $thesis_number);
        mysqli_stmt_execute($stmt_exists);
        $res_exists = mysqli_stmt_get_result($stmt_exists);
        $exists = $res_exists && mysqli_num_rows($res_exists);
        mysqli_stmt_close($stmt_exists);

        if ($row && $exists) {
            $sql2 = "INSERT INTO Grading_Criteria (Thesis_ID, Professor_User_ID, Quality_Goals, Time_Interval, Text_Quality, Presentation)
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt2 = mysqli_prepare($connection, $sql2);
            mysqli_stmt_bind_param($stmt2, "iidddd", $thesis_number, $professor_id, $quality_goals, $time_interval, $text_quality, $presentation);
            mysqli_stmt_execute($stmt2);
            $affected = mysqli_stmt_affected_rows($stmt2);
            mysqli_stmt_close($stmt2);
            $message = '<div class="alert alert-success text-center"> Οι βαθμολογίες έχουν προστεθεί επιτυχώς.</div>';

            if ($affected > 0) {
                $total_grade = ($quality_goals * 0.60) + ($time_interval * 0.15) + ($text_quality * 0.15) + ($presentation * 0.10);
                $sql3 = "SELECT FIELD(?, Trimelis_Professor_1, Trimelis_Professor_2, Trimelis_Professor_3) AS position FROM trimelis WHERE Thesis_ID = ?";
                $stmt3 = mysqli_prepare($connection, $sql3);
                mysqli_stmt_bind_param($stmt3, "ii", $professor_id, $thesis_number);
                mysqli_stmt_execute($stmt3);
                mysqli_stmt_bind_result($stmt3, $position);
                mysqli_stmt_fetch($stmt3);
                mysqli_stmt_close($stmt3);

                $ProfessorMap = [
                    1 => 'Trimelis_Professor_1_Grade',
                    2 => 'Trimelis_Professor_2_Grade',
                    3 => 'Trimelis_Professor_3_Grade',
                ];
                $professor = $ProfessorMap[(int)$position];

                $sql4 = "UPDATE Trimelis_Vathmologia SET $professor = ? WHERE Thesis_ID = ?";
                $stmt4 = mysqli_prepare($connection, $sql4);
                mysqli_stmt_bind_param($stmt4, "di", $total_grade, $thesis_number);
                mysqli_stmt_execute($stmt4);
                mysqli_stmt_close($stmt4);
                $message = '<div class="alert alert-success text-center"> Η τελική βαθμολογία έχει καταχωρηθεί.</div>';

                $sql5 = "UPDATE Trimelis_Vathmologia
                         SET Trimelis_Final_Grade = (Trimelis_Professor_1_Grade + Trimelis_Professor_2_Grade + Trimelis_Professor_3_Grade)/3 WHERE Thesis_ID = ?
                         AND Trimelis_Professor_1_Grade IS NOT NULL
                         AND Trimelis_Professor_2_Grade IS NOT NULL
                         AND Trimelis_Professor_3_Grade IS NOT NULL";
                $stmt5 = mysqli_prepare($connection, $sql5);
                mysqli_stmt_bind_param($stmt5, "i", $thesis_number);
                mysqli_stmt_execute($stmt5);
                $affected2 = mysqli_stmt_affected_rows($stmt5);
                mysqli_stmt_close($stmt5);

                if ($affected2 > 0){
                    $message = '<div class="alert alert-success text-center"> Ο συνολικός βαθμός της τριμελούς επιτροπής ενημερώθηκε.</div>';
                }
            } else {
                $message = '<div class="alert alert-warning text-center">Δεν καταχωρήθηκαν οι βαθμοί.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger text-center"> Αυτός ο φοιτητής δεν υπάρχει ή δεν είστε μέλος τριμελούς επιτροπής της συγκεκριμένης διπλωματικής εργασίας ή δεν είναι υπό ανάθεση.</div>';

            if (!$exists){
                $message = '<div class="alert alert-warning text-center">Δεν ενεργοποιήθηκε η καταχώρηση βαθμών από τον επιμελητή.</div>';
            }

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
    <title>Καταχώρηση Βαθμού Διπλωματικής Εργασίας ως Μέλος Τριμελούς Επιτροπής</title>
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

   <main class="col-12 col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4 pt-4">
    <div class="card shadow-lg p-4">
        <h2 class="mb-4 text-center text-primary">Καταχώρηση Βαθμού Διπλωματικής Εργασίας ως Μέλος Τριμελούς Επιτροπής</h2>
        
       <?php
    if (!empty($message)) {
        echo $message;
    }
    ?>

        <form method="POST" action="thesis_grading.php" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="student_am" class="form-label fw-bold">Εισαγωγή Αριθμού Φοιτητή τον οποίο αφορά η αναλυτική βαθμολόγηση:</label>
                <input type="text"class="form-control" id="student_am" name="student_am"  placeholder="Πληκτρολογήστε τον Αριθμό του Φοιτητή..." required>
            </div>

            <div class="mb-3">
                <label for="quality_goals"  class="form-label fw-bold">Βαθμός για ποιότητα της Δ.Ε. και βαθμός εκπλήρωσης των στόχων της:</label>
                <input type="number" id="quality_goals" class="form-control" name="quality_goals" placeholder="Βαθμός..." min="0" max="10" step="0.1" required>
            </div>

            <div class="mb-3">
                <label for="time_interval"  class="form-label fw-bold">Βαθμός για χρονικό διάστημα εκπόνησής της:</label>
                <input type="number" id="time_interval" class="form-control" name="time_interval" placeholder="Βαθμός..." min="0" max="10" step="0.1" required>
            </div>

            <div class="mb-3">
                <label for="text_quality"  class="form-label fw-bold">Βαθμός για ποιότητα και πληρότητα του κειμένου της εργασίας και των υπολοίπων παραδοτέων της:</label>
                <input type="number" id="text_quality" class="form-control" name="text_quality" placeholder="Βαθμός..." min="0" max="10" step="0.1" required>
            </div>

            <div class="mb-3">
                <label for="presentation"  class="form-label fw-bold">Βαθμός για συνολική εικόνα της παρουσίασης:</label>
                <input type="number" id="presentation" class="form-control" name="presentation" placeholder="Βαθμός..." min="0" max="10" step="0.1" required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="professor_grading" name="professor_grading" class="btn btn-primary btn-lg">
                    Καταχώρηση Αναλυτικής Βαθμολόγησης
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>