<?php
session_start();

include("database_connection.php");
include("functions.php");

if (isset($_SESSION['role']) && $_SESSION['role'] === "professor") {
    $user = login_session_professor($connection);
    $_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
    $_SESSION['email'] = $user['Professor_email'] ;

    $trimelis = $user['Professor_User_ID'];

    $sql = "SELECT draft_thesis.Thesis_ID, draft_thesis.draft_thesis_pdf, thesis.Thesis_Student FROM draft_thesis
    INNER JOIN thesis ON draft_thesis.Thesis_ID = thesis.Thesis_ID
    INNER JOIN trimelis ON trimelis.Thesis_ID = draft_thesis.Thesis_ID
    LEFT  JOIN professor p1 ON p1.Professor_User_ID = trimelis.Trimelis_Professor_1
    LEFT  JOIN professor p2 ON p2.Professor_User_ID = trimelis.Trimelis_Professor_2
    LEFT  JOIN professor p3 ON p3.Professor_User_ID = trimelis.Trimelis_Professor_3
    WHERE /*thesis.Thesis_Status = 'under_review' AND */(trimelis.Trimelis_Professor_1 = ? OR trimelis.Trimelis_Professor_2 = ? OR trimelis.Trimelis_Professor_3 = ?)";
    $stmt = $connection->prepare($sql);
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }
    $stmt->bind_param("iii",  $trimelis, $trimelis, $trimelis);
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
    <title>Προβολή πρόχειρου κειμένου φοιτητή ως μέλος τριμελούς</title>
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
        <h2 class="mb-4 text-center text-primary">Σημειώσεις</h2>
        
    <?php
        if (!empty($message)) {
            echo $message;
        }
    ?>

 <div class="card shadow-sm">
  <div class="card-header"><strong>Σημειώσεις φοιτητών για διπλωματικές που είστε μέλος</strong></div>
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead>
        <tr>
          <th>Thesis ID</th>
          <th>Student (AM)</th>
          <th>Πρόχειρο Κείμενο</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$rows): ?>
          <tr><td colspan="9" class="text-center text-muted">Δεν βρέθηκαν εγγραφές.</td></tr>
        <?php else: ?>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= (int)$r['Thesis_ID'] ?></td>
              <td><?= htmlspecialchars($r['Thesis_Student']) ?></td>
              <td>
                <?php if (!empty($r['draft_thesis_pdf'])): ?>
                    <a href="<?= htmlspecialchars($r['draft_thesis_pdf']) ?>" 
                    target="_blank" 
                    class="btn btn-primary btn-sm">
                    Προβολή PDF
                    </a>
                <?php else: ?>
                    <span class="text-muted">Δεν έχει ανέβει αρχείο</span>
                <?php endif; ?>
               </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>