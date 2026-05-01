<?php
// PHP code αν χρειαστεί
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ανακοινώσεις</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4 text-center" style="max-width: 500px; width: 100%;">
    <h2 class="mb-4">Φιλτράρισμα με βάση την Ημερομηνία</h2>
    
    <div class="mb-3">
      <label class="form-label">Αρχική Ημερομηνία:</label>
      <input type="date" id="startDate" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">Τελική Ημερομηνία:</label>
      <input type="date" id="endDate" class="form-control">
    </div>

    <div class="d-flex justify-content-center gap-2">
      <button id="filterBtn" class="btn btn-primary">Φιλτράρισμα</button>
    </div>

    <pre id="output" class="bg-light border mt-4 p-3 text-start" style="max-height: 300px; overflow:auto;"></pre>
       <div class="d-flex justify-content-center gap-2">
      <a href="login.php" class="btn btn-success">Αρχική Σελίδα για Σύνδεση στο σύστημα</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.getElementById("filterBtn").addEventListener("click", () => {
      const start = new Date(document.getElementById("startDate").value);
      const end   = new Date(document.getElementById("endDate").value);

      fetch("announcements_data.json")
        .then(res => res.json())
        .then(data => {
          const filtered = data.filter(item => {
            const current = new Date(item.date);
            return current >= start && current <= end;
          });

          document.getElementById("output").textContent =
            JSON.stringify(filtered, null, 2);
        })
        .catch(err => console.error("Error:", err));
    });
  </script>

</body>
</html>
