<?php
session_start();

include("database_connection.php");
include("functions.php");

$user = login_session_professor($connection);
$_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
$_SESSION['email'] = $user['Professor_email'] ;


if (isset($_SESSION['role']) && ($_SESSION['role'] === "professor")){
    

$invites = [];
$query  = "SELECT * FROM Trimelous_invitation WHERE Invitation_Status = 'Pending' AND Professor_User_id = '" . $user['Professor_User_ID'] . "'  " ;
$result = $connection->query($query);
// $thes_id = $query = "SELECT Thesis_ID FROM Trimelous_thesis WHERE Professor_User_id = '" . $user['Professor_User_ID'] . "'";

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $invites[] = $row; //εδω θα πρεπει να γινει ενα query για να παρουμε τα στοιχεια του φοιτητη
    }
} else {
    // handle query error however you like:
    $error = $connection->error;
}




}
else {
    // If the user is not logged in as a professor, redirect to the login page
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Προβολή προσκλήσεων συμμετοχής σε τριμελή</title>
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
    <h2 class="mb-4 text-center text-primary">Προβολή προσκλήσεων συμμετοχής σε τριμελή επιτροπή</h2>
  <?php if (empty($invites)): ?>
    <p>Καμία πρόσκληση συμμετοχής σε τριμελή διπλωματικής εργασίας.</p>
  <?php else: ?>
    <form id="invitesForm">
      <?php foreach ($invites as $inv): ?>
        <div class="invite">
           <div class="invite border-bottom pb-3 mb-3"></div>
            <p><strong>Αριθμός Μητρώου Φοιτητή:</strong> <?= htmlspecialchars($inv['Thesis_Student_Number']) ?></p>
            <p><strong>Κωδικός Διπλωματικής:</strong> <?= htmlspecialchars($inv['Thesis_ID']) ?></p>
          <p><strong>Κατάσταση Διπλωματικής:</strong> 
            <span class="status"><?= htmlspecialchars($inv['Invitation_Status']) ?></span>
          </p>

         
          <input type="hidden" name="thesis_id" value="<?= (int)$inv['Thesis_ID'] ?>">


          <button type="button" class="response-btn btn btn-success" data-answer="accept">Αποδοχή</button>
          <button type="button" class="response-btn btn btn-danger" data-answer="deny">Άρνηση</button>
        </div>
      <?php endforeach; ?>
    </form>
  <?php endif; ?>

<script>

  document.querySelectorAll('.response-btn').forEach(btn => {
    btn.addEventListener('click', async e => {
      const answer = e.target.dataset.answer; 
      const recordID = e.target.closest('.invite').querySelector('input[name="thesis_id"]').value;

      try {
        const resp = await fetch('update_invitation_trimelis.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({  
              accept: answer === 'accept',
              deny:   answer === 'deny',
              Id: recordID
          })
        });

        const json = await resp.json();
        if (!resp.ok) {
          throw new Error(json?.error || resp.statusText);
        }

        const msg = (answer === 'accept')
          ? 'Η πρόσκληση εγκρίθηκε!'
          : 'Η πρόσκληση απορρίφθηκε.';
        const cls = (answer === 'accept') ? 'alert-success' : 'alert-warning';

        const alertBox = document.createElement('div');
        alertBox.className = `alert ${cls} text-center mt-3`;
        alertBox.textContent = msg;
        document.querySelector('.container').prepend(alertBox);

        
        setTimeout(() => { location.reload(); }, 5000);

      } catch (err) {
        console.error(err);
        const alertBox = document.createElement('div');
        alertBox.className = 'alert alert-danger text-center mt-3';
        alertBox.textContent = 'Πρόβλημα κατά την αποθήκευση. Προσπάθησε ξανά.';
        document.querySelector('.container').prepend(alertBox);
        setTimeout(() => alertBox.remove(), 5000);
      }
    });
  });
</script>
</section>