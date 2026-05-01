<?php
?>
<nav id="sidebarMenu" class="col-md-4 col-lg-3 d-md-block bg-light sidebar collapse">
  <div class="d-flex flex-column vh-100 overflow-auto pt-3 pb-4">

    <!-- Στοιχεία χρήστη -->
    <div class="px-3 mb-3">
      <div class="fw-bold">Μενού Γραμματείας</div>
      <div class="small text-muted">
        <?php if (isset($_SESSION['email'])): ?>
          <div><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']); ?></div>
        <?php endif; ?>
      </div>
    </div>

    <ul class="nav nav-pills flex-column px-2 gap-1 flex-grow-1">

      <!-- Εισαγωγή Δεδομένων -->
      <li class="nav-item text-muted small mt-2">Εισαγωγή Δεδομένων</li>
      <li class="nav-item"><a class="nav-link" href="upload_json.php">Εισαγωγή αρχείου JSON από υπολογιστή </a></li>

      <!-- Προβολή Διπλωματικών -->
      <li class="nav-item text-muted small mt-3">Προβολή Διπλωματικών Εργασιών</li>
      <li class="nav-item"><a class="nav-link" href="secretary_show_all_thesis.php">Προβολή λίστας διπλωματικών</a></li>

      <!-- Διαχείριση Διπλωματικών Εργασιών -->
      <li class="nav-item text-muted small mt-3">Διαχείριση Διπλωματικών Εργασιών</li>

      <!-- Ενεργή -->
      <li class="nav-item text-muted small mt-3">Ενεργή</li>
      <li class="nav-item"><a class="nav-link" href="ap_thesis.php">Καταχώρηση ΑΠ</a></li>
      <li class="nav-item"><a class="nav-link" href="secretary_cancel_thesis.php">Ακύρωση Διπλωματικής Εργασίας</a></li>

      <!-- Υπό Εξέταση -->
      <li class="nav-item text-muted small mt-3">Υπό Εξέταση</li>
      <li class="nav-item"><a class="nav-link" href="thesis_status_done.php">Αλλαγή Κατάστασης Διπλωματικής Εργασίας σε Περατωμένη</a></li>
      
    </ul>

  </div>
</nav>
