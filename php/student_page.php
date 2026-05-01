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
    <title>Αρχική Σελίδα Φοιτητή</title>
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



<main class="col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4">
      <div class="pt-4">
        <h1 class="text-center fw-bold">Καλώς ήρθες στην σελίδα του Φοιτητή!</h1>
        <p class="lead text-center">Εδώ μπορείς να δεις τις επιλογές σου στην Πλατφόρμα:</p>
    </div>



 <div class="container mt-5">
    <div class="row gx-4 gy-4 justify-content-center">
     <!-- Επιλογή 1- Προβολή Διπλωματικής Εργασίας -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
              <div id="upload_json">    
                <div class="card-header text-center fw-bold">Επιλογή 1</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="text-center flex-grow-1 d-flex align-items-center justify-content-center mb-3 fs-6" style="min-height: 100px; font-family: Arial, sans-serif;">
                        Προβολή Πληροφορίες της Διπλωματικής Εργασίας
                    </p>
                    <a class="btn btn-primary w-100" href="student_show_thesis.php" role="button" aria-expanded="false">
                    Μετάβαση
                </a>
                </div>
            </div>
        </div>
        </div>
                         
   <!-- Επιλογή 2-Επεξεργασία Προφίλ -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header text-center fw-bold">Επιλογή 2</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="text-center flex-grow-1 d-flex align-items-center justify-content-center mb-3 fs-6" style="min-height: 100px; font-family: Arial, sans-serif;">
                        Επεξεργασία Προφίλ
                    </p>
                    <a class="btn btn-primary w-100" href="student_profile_edit.php" role="button" aria-expanded="false">
                    Μετάβαση
                </a>
                </div>
        </div>
        </div>

         <!-- Επιλογή 3 Διαχείριση Διπλωματικής Εργασίας -->                    
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header text-center fw-bold">Επιλογή 3</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="text-center flex-grow-1 d-flex align-items-center justify-content-center mb-3 fs-6" style="min-height: 100px; font-family: Arial, sans-serif;">
                       Διαχείριση Διπλωματικής Εργασίας
                    </p>
                    <a class="btn btn-primary w-100" href="student_diaxeirisi.php" role="button" aria-expanded="false">
                    Μετάβαση
                </a>
                </div>
            </div>
        </div>
                   
</div>
</main>
</body>
</html>