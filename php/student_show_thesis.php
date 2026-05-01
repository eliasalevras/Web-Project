<?php
    session_start();

    include("database_connection.php");
    include("functions.php");

    $user = login_session_student($connection);
    $_SESSION['username'] = $user['Student_name'] . " " . $user['Student_surname'];
    $_SESSION['email'] = $user['Student_email'] ;

if (isset($_SESSION['role']) && ($_SESSION['role'] === "student")){
}

else {
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Πληροφοριίες Διπλωματικής Εργασίας</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="btn btn-outline-light me-2 d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle sidebar">
      ☰ Μενού
    </button>

    <a class="navbar-brand">Η Πλατφόρμα</a>
    <a href="student_page.php" class="btn btn-success ms-2">Αρχική</a>

            <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger">Αποσύνδεση</a>
        </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <?php
include "student_sidebar.php";
    ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<main class="col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4 pt-4">
  <div class="card shadow-lg p-4">
    <h2 class="mb-4 text-center text-primary">Πληροφορίες της Διπλωματικής Εργασίας</h2>
    <div id="myThesis" class="w-100"></div>
  </div>
</main>

<script>
$(document).ready(function(){
    // Μόλις φορτώσει η σελίδα, φέρνουμε τα στοιχεία
    $.ajax({
        url: 'fetch_thesis.php',
        type: 'GET',
        success: function(data) {
            $('#myThesis').html(data);
        },
        error: function() {
            $('#myThesis').html("Error loading thesis.");
        }
    });
});
</script>
        <br><br>
        <br><br>
</main>
</div>
</body>
</html>