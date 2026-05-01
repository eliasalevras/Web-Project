<?php

?>
<nav id="sidebarMenu" class="col-md-4 col-lg-3 d-md-block bg-light sidebar collapse">
  <div class="d-flex flex-column vh-100 overflow-auto pt-3 pb-4">

    <!-- Στοιχεία χρήστη -->
    <div class="px-3 mb-3">
      <div class="fw-bold">Μενού Φοιτητή</div>
      <div class="small text-muted">
        <?php if (isset($_SESSION['email'])): ?>
          <div><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']); ?></div>
          <div><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']); ?></div>
        <?php endif; ?>
      </div>
    </div>

    <ul class="nav nav-pills flex-column px-2 gap-1 flex-grow-1">

      <!-- Δημιουργία & Ανάθεση -->
      <li class="nav-item text-muted small mt-2">Προβολή Θέματος Διπλωματικής Εργασίας</li>
      <li class="nav-item"><a class="nav-link" href="student_show_thesis.php">Προβολή Διπλωματικής</a></li>

            
      <!-- Προβολή Διπλωματικών -->
      <li class="nav-item text-muted small mt-3">Επεξεργασία Προφίλ</li>
      <li class="nav-item"><a class="nav-link" href="student_profile_edit.php">Επεξεργασία Προφίλ</a></li>

      <!-- Διαχείριση Διπλωματικής Εργασίας -->
      <li class="nav-item text-muted small mt-3">Διαχείριση Διπλμωατικής Εργασίας</li>
      <li class="nav-item">
        <a class="nav-link d-flex justify-content-between align-items-center" href="student_diaxeirisi.php">Διαχείριση Διπλωματικής</a></li>

      

      
    </ul>

  </div>
</nav>
