<?php
session_start();

include("database_connection.php");
include("functions.php");

if (isset($_SESSION['role']) && $_SESSION['role'] === "professor") {
    $user = login_session_professor($connection);
    $_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
    $_SESSION['email'] = $user['Professor_email'] ;

    $epimelitis = $user['Professor_User_ID'];

    $sql = "SELECT thesis.Thesis_ID, thesis.Thesis_Student, thesis_professor_notes.Notes FROM thesis INNER JOIN trimelis ON thesis.Thesis_ID = trimelis.Thesis_ID
    INNER JOIN thesis_professor_notes ON thesis.Thesis_ID = thesis_professor_notes.Thesis_ID 
    WHERE /*thesis.Thesis_Status = 'active' AND */ (trimelis.Trimelis_Professor_1 = ? OR trimelis.Trimelis_Professor_2 = ? OR trimelis.Trimelis_Professor_3 = ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iii",  $epimelitis, $epimelitis, $epimelitis);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
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
    <title>Σημειώσεις Διπλωματικών Εργασιών</title>
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
        <h2 class="mb-4 text-center text-primary">Σημειώσεις Διπλωματικών Εργασιών</h2>
        
    <?php
        if (!empty($message)) {
            echo $message;
        }
    ?>

  <div class="card shadow-sm">
  <div class="card-header"><strong>Οι Σημειώσεις σας ως επιμελητής ή μέλος τριμελούς</strong></div>
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead>
        <tr>
          <th>Thesis ID</th>
          <th>Thesis Student (AM)</th>
          <th>Thesis Notes</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$rows): ?>
          <tr><td colspan="3" class="text-center text-muted">Δεν βρέθηκαν σημειώσεις.</td></tr>
        <?php else: ?>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= (int)$r['Thesis_ID'] ?></td>
              <td><?= htmlspecialchars($r['Thesis_Student']) ?></td>
              <td><?= htmlspecialchars($r['Notes']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>