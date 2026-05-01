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

            <div class="mb-3">
                <label for="student_am" class="form-label fw-bold">Εισαγωγή αριθμού φοιτητή για επεξεργασία διπλωματικής εργασίας:</label>
                <input type="text"class="form-control" id="student_am" name="student_am"  placeholder="Πληκτρολογήστε τον αριθμό του Φοιτητή..." required>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="edit_thesis" name="edit_thesis_by_am" class="btn btn-primary btn-lg">
                    Επεξεργασία Δηπλωματικής
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>