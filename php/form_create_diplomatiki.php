
<?php
session_start();


if (!empty($_SESSION['success'])) {
//  header("refresh:5; url=./professor_page.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Δημιουργία Διπλωματικής Εργασίας</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
      <div id="Create_Diplomatiki" class="card shadow-lg p-4">
        <h2 class="mb-4 text-center text-primary">Δημιουργία Διπλωματικής Εργασίας</h2>

          <?php if (!empty($_SESSION['success'])): ?>
    <div id="flashMessage" class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['error'])): ?>
    <div id="flashMessage" class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  
                   
        <form method="POST" action="create_thesis.php" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="thesis_title" class="form-label fw-bold">Τίτλος Διπλωματικής:</label>
                <input type="text" class="form-control" id="thesis_title" name="thesis_title" placeholder="Πληκτρολογήστε τον τίτλο" required>
            </div>

            <div class="mb-3">
                <label for="thesis_description" class="form-label fw-bold">Περιγραφή Διπλωματικής:</label>
                <textarea class="form-control" id="thesis_description" name="thesis_description" rows="4" placeholder="Πληκτρολογήστε μια αναλυτική περιγραφή" required></textarea>
            </div>

            <div class="mb-3">
                <label for="thesis_pdf" class="form-label fw-bold">Αναλυτική Περιγραφή (PDF):</label>
                <input type="file" class="form-control" id="thesis_pdf" name="thesis_pdf" accept="application/pdf">
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="create_thesis" name="create_thesis" class="btn btn-primary btn-lg">
                    Δημιουργία
                </button>
            </div>

        </form>
    </div>
</div>

<script>

    // Αυτόματο κλείσιμο alert μετά από 5"
    document.addEventListener('DOMContentLoaded', function () {
      const flash = document.getElementById('flashMessage');
      if (flash) {
        setTimeout(function () {
          const bsAlert = new bootstrap.Alert(flash);
          bsAlert.close();
        }, 5000);
      }
    });
  </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>