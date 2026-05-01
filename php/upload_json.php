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
   
        // exit;
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
    <title>Ανέβασμα JSON αρχείου</title>
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

<?php
// Ελέγχει αν υπάρχει μήνυμα κατάστασης
if (isset($_SESSION['status'])) {
    // Εμφανίζει το μήνυμα μέσα σε ένα πλαίσιο
    echo '<div class="container container-custom mt-3"><div class="alert alert-info text-center" role="alert">' . htmlspecialchars($_SESSION['status']) . '</div></div>';
    // Καθαρίζει το μήνυμα από τη συνεδρία για να μην εμφανιστεί ξανά
    unset($_SESSION['status']);
}
?>

    <main class="col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4 pt-4">
      <div class="card shadow-lg p-4">
        <h2 class="mb-4 text-center text-primary">Εισαγωγή Δεδομένων Φοιτητών και Καθηγητών</h2>
    
        <form method="POST" id="upload_json" enctype="multipart/form-data" class="border p-4 rounded bg-light">
             <div class="mb-3">
                <label for="json_file" class="form-label fw-bold">Επιλογή JSON αρχείου</label>
                <input type="file" class="form-control" id="json_file" name="json_file" accept=".json" required>
            </div>
        
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5">Εισαγωγή JSON αρχείου</button>
            </div>
        </form>
    </div>
    </main>
</div>
</body>
</html>