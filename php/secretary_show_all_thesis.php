<?php
session_start();

include("database_connection.php");
include("functions.php");
include("json_to_sql.php");

$user = login_session_secretary($connection);
$_SESSION['email'] = $user['Secretary_name'] . " " . $user['Secretary_surname'];


if (isset($_SESSION['role']) && ($_SESSION['role'] === "secretary")){
    if (isset($_POST['load_json_from_url'])){
        url();
    } elseif (isset($_FILES['json_file'])){
        $file = $_FILES['json_file'];
        upload($file);
    }
}
else {
    //Redirect to previous page (No access if role is not professor)
    //header('Location: ' . $_SERVER['HTTP_REFERER']);
    // Output JavaScript to redirect back
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
    <title>Αρχική Σελίδα Γραμματείας</title>
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
        <h2 class="mb-4 text-center text-primary">Προβολή Διπλωματικών Εργασιών</h2>


<div id="ajaxButton" class="mt-3">
    <!-- Η λίστα από fetch_thesis.php θα εμφανιστεί εδώ -->
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    function fetchTopics() {
        $.ajax({
            url: 'fetch_thesis.php',
            type: 'GET',
            success: function(data) {
                $('#ajaxButton').html(data);
            },
            error: function(xhr, status, error) {
                $('#ajaxButton').html('<p class="text-danger">Σφάλμα κατά τη φόρτωση των θεμάτων.</p>');
            }
        });
    }

    fetchTopics();
});
</script>

    </div>
    </main>
    </div>
    </div>
    </body>
    </html>
