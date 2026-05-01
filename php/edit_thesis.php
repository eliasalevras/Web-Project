<?php
session_start();

include("database_connection.php");
include("functions.php");

if (isset($_SESSION['role']) && $_SESSION['role'] === "professor") {
    $user = login_session_professor($connection);
    $_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
    $_SESSION['email'] = $user['Professor_email'] ;
    
    $epimelitis = $user['Professor_User_ID'];
    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['edit_thesis_by_am'])) {
        $student_am = $_POST['student_am'];
        $sql = "SELECT * FROM thesis WHERE Thesis_Student = ? AND Thesis_Epimelitis = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $student_am, $epimelitis);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row   = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        $thesis_title = '';
        $thesis_desc = '';

        if ($row) {
            $thesis_number = $row['Thesis_ID'];
            $thesis_title = $row['Thesis_Title'];
            $thesis_desc = $row['Thesis_Description'];
        } else {
           $message = "<div class='alert alert-danger text-center'> Δεν είστε ο επιμελητής ή δεν υπάρχει αυτός ο φοιτητής με ΑΜ: $student_am ! " . mysqli_error($connection) . " </div>"; 
        //Redirect after 5 seconds to the professor page
        header("refresh:5; url=./professor_page.php");
        }
    }

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit_thesis']))
        {   
            $student_am = $_POST['student_am'];
            $thesis_title = $_POST['thesis_title'];
            $thesis_desc = $_POST['thesis_description'];
            //Update thesis
            $sql2 = "UPDATE thesis SET Thesis_Title = ?, Thesis_Description = ? WHERE Thesis_Student = ? AND Thesis_Epimelitis = ?";
            $stmt2 = mysqli_prepare($connection, $sql2);
            mysqli_stmt_bind_param($stmt2, "ssii", $thesis_title, $thesis_desc, $student_am, $epimelitis);
            mysqli_stmt_execute($stmt2);
            $affected = mysqli_stmt_affected_rows($stmt2);
            mysqli_stmt_close($stmt2);

            if ($affected > 0) {
                $message = '<div class="alert alert-success text-center"> Επιτυχής επεξεργασία διπλωματικής εργασίας.</div>';
            } else {
                $message = '<div class="alert alert-danger text-center"> Η επεξεργασία διπλωματικής εργασίας απέτυχε ή δεν έγιναν αλλαγές. Ο τίτλος πρέπει να είναι μοναδικός.</div>';
            }
        //Redirect after 5 seconds to the professor page
        header("refresh:5; url=./professor_page.php");
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
    <title>Επεξεργασία Διπλωματικής Εργασίας ως Επιβλέπων</title>
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
        <h2 class="mb-4 text-center text-primary">Επεξεργασία Διπλωματικής Εργασίας ως Επιβλέπων</h2>
        
        <?php if (!empty($message)) echo $message; ?>

       <form method="POST" action="edit_thesis.php" enctype="multipart/form-data">
            <input type="hidden" name="student_am" value="<?php echo htmlspecialchars($student_am)?>">
            <div class="mb-3">
                <label for="thesis_title" class="form-label fw-bold">Τίτλος Διπλωματικής:</label>
                <input type="text" class="form-control" id="thesis_title" name="thesis_title" value="<?php echo htmlspecialchars($thesis_title)?>">
            </div>

            <div class="mb-3">
                <label for="thesis_description" class="form-label fw-bold">Περιγραφή Διπλωματικής:</label>
                <textarea class="form-control" id="thesis_description" name="thesis_description" rows="4"><?php echo htmlspecialchars($thesis_desc)?></textarea>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="edit_thesis" name="edit_thesis" class="btn btn-primary btn-lg">
                    Επεξεργασία
                </button>
            </div>

        </form>
    </div>
</div>
</body>
</html>