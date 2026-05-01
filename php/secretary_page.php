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

  <style>
    .sidebar { min-height: 100vh; }
    @media (max-width: 767.98px){
      main { padding-top: 1rem; }
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

    <main class="col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4">
      <div class="pt-4">
        <h1 class="text-center fw-bold">Καλώς ήρθες στην σελίδα της Γραμματείας!</h1>
        <p class="lead text-center">Εδώ μπορείς να δεις τις επιλογές σου στην Πλατφόρμα:</p>
    </div>

 <div class="container mt-4">
    <div class="row g-4">


        <!-- Επιλογή 1 Φόρτωση αρχείου JSON για πληροφορίες Φοιτητών και Καθηγητών από το αποθετήριο του Πανεπιστημίου -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div id="load_json">
                <?php if (!empty($_SESSION['status'])): ?>
                     <div id="alertBox" class="alert alert-info text-center">
                         <?php 
                             echo htmlspecialchars($_SESSION['status']); 
                             unset($_SESSION['status']); // σβήνεται ώστε να μην ξαναφαίνεται με refresh
                         ?>
                    </div>
                <?php endif; ?>
                <div class="card-header text-center fw-bold">Επιλογή 1</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="text-center flex-grow-1 d-flex align-items-center justify-content-center mb-3 fs-6" style="min-height: 100px; font-family: Arial, sans-serif;">
                        Φόρτωση αρχείου JSON για πληροφορίες Φοιτητών και Καθηγητών από το αποθετήριο του Πανεπιστημίου
                    </p>
                    <form method="POST" class="w-100">
                        <input type="submit" class="btn btn-primary w-100" name="load_json_from_url" value="Φόρτωση JSON από URL">
                    </form>
                </div>
            </div>
        </div>
        </div>

        <!-- Επιλογή 2 Φόρτωση αρχείου JSON από τον υπολογιστή σας -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
              <div id="upload_json">    
                <div class="card-header text-center fw-bold">Επιλογή 2</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="text-center flex-grow-1 d-flex align-items-center justify-content-center mb-3 fs-6" style="min-height: 100px; font-family: Arial, sans-serif;">
                        Φόρτωση αρχείου JSON από τον υπολογιστή σας
                    </p>
                    <a class="btn btn-primary w-100" href="upload_json.php" role="button" aria-expanded="false">
                    Μετάβαση
                    </a>
                </div>
            </div>
        </div>
        </div>

        <!-- Επιλογή 3 Καταχώρηση ΑΠ -->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header text-center fw-bold">Επιλογή 3</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="text-center flex-grow-1 d-flex align-items-center justify-content-center mb-3 fs-6" style="min-height: 100px; font-family: Arial, sans-serif;">
                        Καταχώρηση ΑΠ
                    </p>
                    <a class="btn btn-primary w-100" href="ap_thesis.php" role="button" aria-expanded="false">
                    Μετάβαση
                </a>
            </div>
            </div>
        </div>

         <!-- Επιλογή 4 Ακύρωση Διπλωματικής Εργασίας-->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header text-center fw-bold">Επιλογή 4</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="text-center flex-grow-1 d-flex align-items-center justify-content-center mb-3 fs-6" style="min-height: 100px; font-family: Arial, sans-serif;">
                        Ακύρωση Διπλωματικής Εργασίας
                    </p>
                     <a class="btn btn-primary w-100" href="secretary_cancel_thesis.php" role="button" aria-expanded="false">
                    Μετάβαση
                </a>
            </div>
            </div>
        </div>
           

        <!-- Επιλογή 5 Αλλαγή Κατάστασης Διπλωματικής Εργασίας σε Περατωμένη.-->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header text-center fw-bold">Επιλογή 5</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="text-center flex-grow-1 d-flex align-items-center justify-content-center mb-3 fs-6" style="min-height: 100px; font-family: Arial, sans-serif;">
                        Αλλαγή Κατάστασης Διπλωματικής Εργασίας σε Περατωμένη.
                    </p>
                     <a class="btn btn-primary w-100" href="thesis_status_done.php" role="button" aria-expanded="false">
                    Μετάβαση
                </a>
            </div>
            </div>
        </div>

        <!-- Επιλογή 6  Προβολή ΔΕ-->
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header text-center fw-bold">Επιλογή 6</div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p class="text-center flex-grow-1 d-flex align-items-center justify-content-center mb-3 fs-6" style="min-height: 100px; font-family: Arial, sans-serif;">
                       Προβολή Διπλωματικών Εργασιών
                    </p>
                      <a class="btn btn-primary w-100" href="secretary_show_all_thesis.php" role="button" aria-expanded="false">
                    Μετάβαση
                </a>
            </div>
            </div>
        </div>

 </div>
</div>
</body>
</html>

<script>
    // Εξαφανίζεται το alert μετά από 5 δευτερόλεπτα
    setTimeout(function () {
        let alertBox = document.getElementById("alertBox");
        if (alertBox) {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 500); // το βγάζει τελείως
        }
    }, 4000);
</script>

