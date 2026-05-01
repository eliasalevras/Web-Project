<?php

session_start();

include("database_connection.php");
include("functions.php");
    $user = login_session_student($connection);
    $_SESSION['username'] = $user['Student_name'] . " " . $user['Student_surname'];
    $_SESSION['email'] = $user['Student_email'] ;

    
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "student") {
  echo '<script>alert("You do not have permission to access this page."); history.back();</script>';
  exit;
}

// ---- Logged-in student
$user = login_session_student($connection);
$student_am = (int)$user['Student_number'];

// ---- Fetch thesis for this student
$sql = "SELECT t.*, p.Professor_name, p.Professor_surname
        FROM Thesis t
        LEFT JOIN Professor p ON t.Thesis_Epimelitis = p.Professor_User_ID
        WHERE t.Thesis_Student = ? LIMIT 1";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $student_am);
$stmt->execute();
$thesis = $stmt->get_result()->fetch_assoc();
$stmt->close();

$thesisId = (int)$thesis['Thesis_ID'];
$status   = $thesis['Thesis_Status']; // pending | under_review | ready | cancel

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



  <main class="col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4 pt-4">
    <div id="Create_Diplomatiki" class="card shadow-lg p-4">
      <h2 class="mb-4 text-center text-primary">Διαχείριση Διπλωματικής Εργασίας</h2>

    <section>
      <div class="card shadow-sm mt-4">
        <div class="card-header">
          <strong>Στοιχεία Διπλωματικής Εργασίας</strong>
        </div>

        <div class="table-responsive">
          <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Τίτλος Διπλωματικής</th>
                <th>Περιγραφή Διπλωματικής</th>
                <th>Κατάσταση</th>
                <th>Επιμελητής</th>
              </tr>
            </thead>
            <tbody>
            <?php if (empty($thesis)): ?>
              <tr>
                <td colspan="4" class="text-center text-muted">Δεν υπάρχουν στοιχεία διπλωματικής.</td>
              </tr>
            <?php else: ?>
              <tr>
                <td><?= htmlspecialchars($thesis['Thesis_Title'] ?? '') ?></td>
                <td><?= nl2br(htmlspecialchars($thesis['Thesis_Description'] ?? '')) ?></td>
                <td>
                  <span class="badge bg-primary"><?= htmlspecialchars($status ?? '') ?></span>
                </td>
                <td><?= htmlspecialchars(($thesis['Professor_name'] ?? '').' '.($thesis['Professor_surname'] ?? '')) ?></td>
              </tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>


    <!--Η ΔΙΠΛΩΜΑΤΙΚΗ ΕΙΝΑΙ READY-ΠΕΡΑΤΩΜΕΝΗ ΚΑΤΑΣΤΑΣΗ -->
    <?php if (($status ?? '') === 'ready'): ?>
      <hr>
      <p class="fs-5">
        <strong>Τελικός Βαθμός:</strong>
        <?= htmlspecialchars($thesis['Thesis_Final_Grade'] ?? '') ?>
      </p>

      <h5 class="mt-4 text-secondary text-center">
        Η διπλωματική σας εργασία έχει ολοκληρωθεί με επιτυχία και είναι πλέον περατωμένη!
      </h5>
      <p class="text-center text-muted">Συγχαρητήρια! Καλή σταδιοδρομία!</p>

    <!--Η ΔΙΠΛΩΜΑΤΙΚΗ ΕΙΝΑΙ ΑΚΥΡΩΜΕΝΗ -->
    <?php elseif (($status ?? '') === 'cancel'): ?>
      <hr>
      <h5 class="mt-4 text-secondary text-center">Η Διπλωματική Εργασία είναι Ακυρωμένη!</h5>
      <p class="text-center text-muted">Επικοινωνήστε με τον επιβλέποντα για τυχόν διευκρινίσεις ή επόμενα βήματα.</p>

    <!--Η ΔΙΠΛΩΜΑΤΙΚΗ ΕΙΝΑΙ ΥΠΟ-ΑΝΑΘΕΣΗ(PENDING)-->
    <?php elseif (($status ?? '') === 'pending'): ?>
      <hr>
      <div id="responsePending" class="mt-3"></div>

      <h5 class="mt-4 text-secondary">Επιλογή Τριμελούς</h5>
      <form id="trimelisForm" class="p-3 border rounded bg-light">
        <div class="mb-3">
          <label for="professors" class="form-label fw-semibold">Επιλογή 1:</label>
          <select id="professors" name="professors" class="form-select" autocomplete="off"></select>
        </div>

        <div class="mb-3">
          <label for="professors2" class="form-label fw-semibold">Επιλογή 2:</label>
          <select id="professors2" name="professors2" class="form-select" autocomplete="off"></select>
        </div>

        <div class="d-flex justify-content-center">
          <button type="submit" class="btn btn-primary">Αποστολή Προσκλήσεων</button>
        </div>
      </form>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script>
      $(function () {
        let professorsData = [];

        $.ajax({
          url: 'get_professorsList.php',
          type: 'GET',
          dataType: 'json',
          success: function (data) {
            if (Array.isArray(data)) {
              professorsData = data.slice();
              let options = '<option value="">-- Select Professor --</option>';
              data.forEach(function (prof) {
                let id = (prof.id ?? prof.Professor_User_ID ?? '').toString();
                let name = (prof.name ?? prof.first_name ?? '').toString();
                let surname = (prof.surname ?? prof.last_name ?? '').toString();
                let label = (name + ' ' + surname).trim() || '(χωρίς όνομα)';
                if (id) options += '<option value="' + id + '">' + label + '</option>';
              });

              $('#professors').html(options);
              $('#professors2').html(options);
              renderSecond();
            } else {
              $('#responsePending').text('Error loading professors data.');
            }
          },
          error: function () {
            $('#responsePending').text('An error occurred while loading the professors.');
          }
        });

        $('#professors').on('change', renderSecond);

        function renderSecond() {
          let chosen1 = $('#professors').val() || '';
          let opts2 = '<option value="">-- Select Professor --</option>';

          professorsData.forEach(function (p) {
            let id = (p.id ?? p.Professor_User_ID ?? '').toString();
            let name = (p.name ?? p.first_name ?? '').toString();
            let surname = (p.surname ?? p.last_name ?? '').toString();
            let label = (name + ' ' + surname).trim() || '(χωρίς όνομα)';

            if (id && id !== chosen1) {
              opts2 += '<option value="' + id + '">' + label + '</option>';
            }
          });

          $('#professors2').html(opts2);
        }

        let sending = false;
        $('#trimelisForm').on('submit', function (e) {
          e.preventDefault();
          if (sending) return;

          let p1 = $('#professors').val();
          let p2 = $('#professors2').val();

          if (!p1 || !p2) {
            alert('Πρέπει να επιλέξεις και τους δύο καθηγητές.');
            return;
          }

          sending = true;
          $('#responsePending').text('Αποστολή...');

          $.ajax({
            url: 'send_invitation_trimelis.php',
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({ professors: [p1, p2] }),
          })
          .done(function (res) {
            $('#responsePending').text(res?.message || 'Η πρόσκληση στάλθηκε επιτυχώς.');
          })
          .fail(function () {
            $('#responsePending').text('Σφάλμα στην αποστολή.');
          })
          .always(function () {
            sending = false;
          });
        });
      });
      </script>

    <!-- ============== UNDER REVIEW (Υπο εξέταση) ============== -->
    <?php elseif (($status ?? '') === 'under_review'): ?>
      <hr>
      <div class="container container-custom mt-5">
        <div class="card shadow-lg p-4">
          <h2 class="mt-3">Προσθήκη Πρόχειρου Κειμένου Διπλωματικής</h2>

          <form id="draft_material" enctype="multipart/form-data" class="mb-4">
             <input type="url" id="thesis_links" name="thesis_links" placeholder="Link" class="form-control mb-2">
             <input type="file" id="draft_thesis_PDF" name="draft_thesis_PDF" accept="application/pdf" required class="form-control mb-2">
            <button type="submit" class="btn btn-primary">Submit</button>
           </form>
        </div>
      </div>
      <hr>
      <div class="container container-custom mt-5">
        <div class="card shadow-lg p-4">
          <h2 class="mt-3">Ορισμός Ημερομηνίας Παρουσίασης</h2>

          <form id="pres_details" class="p-3 border rounded bg-light mt-3">
            <div class="mb-3">
              <label for="pres_date" class="form-label fw-semibold">Ημερομηνία:</label>
              <input type="date" id="pres_date" name="pres_date" class="form-control rounded-3 shadow-sm">
            </div>

            <div class="mb-3">
              <label for="pres_time" class="form-label fw-semibold">Ώρα:</label>
              <input type="time" id="pres_time" name="pres_time" class="form-control rounded-3 shadow-sm">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Τρόπος εξέτασης:</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="exam_type" id="exam_in_person" value="in_person" checked>
                <label class="form-check-label" for="exam_in_person">Δια ζώσης</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="exam_type" id="exam_online" value="online">
                <label class="form-check-label" for="exam_online">Διαδικτυακά</label>
              </div>
            </div>

            <div class="mb-3" id="room_field">
              <label for="room" class="form-label fw-semibold">Αίθουσα:</label>
              <input type="text" id="room" name="room" placeholder="Π.χ. Αίθουσα 101" class="form-control rounded-3 shadow-sm">
            </div>

            <div class="mb-3 d-none" id="link_field">
              <label for="press_link" class="form-label fw-semibold">Σύνδεσμος Παρουσίασης:</label>
              <input type="url" id="press_link" name="press_link" placeholder="Επικολλήστε link εδώ..." class="form-control rounded-3 shadow-sm">
            </div>

            <button type="submit" class="btn btn-primary w-100">Υποβολή</button>
          </form>
        </div>
      </div>

      <script>
      document.addEventListener("DOMContentLoaded", () => {
        const inPersonRadio = document.getElementById("exam_in_person");
        const onlineRadio   = document.getElementById("exam_online");
        const roomField     = document.getElementById("room_field");
        const linkField     = document.getElementById("link_field");

        function toggleFields() {
          if (inPersonRadio.checked) {
            roomField.classList.remove("d-none");
            linkField.classList.add("d-none");
          } else {
            roomField.classList.add("d-none");
            linkField.classList.remove("d-none");
          }
        }

        toggleFields();

        inPersonRadio.addEventListener("change", toggleFields);
        onlineRadio.addEventListener("change", toggleFields);
      });
      </script>

      <hr>

      <div class="container container-custom mt-5">
        <div class="card shadow-lg p-4">
          <h2 class="mb-3">Νημέρτης</h2>
    <form id="nimertis_form" action = "nimertis_post.php" method="POST">
    <input type="link" 
           id="nimertis_link" 
           name="nimertis_link" 
           placeholder="Link of nimertis">
    <input type="submit" value="Submit">
    </form>';

    <?php endif; ?>

  </div> 
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../JavaScript/trimelis_invitation.js" defer></script>
<script src="../JavaScript/thesis_draft.js" defer></script>
<script src="../JavaScript/presentation.js" defer></script>

</body>
</html>