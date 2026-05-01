<?php
session_start();
include("database_connection.php");
include("functions.php");

$labels_chart_1 = $values_chart_1 = [];
$labels_chart_2 = $values_chart_2 = [];
$labels_chart_3 = $values_chart_3 = [];

if (isset($_SESSION['role']) && $_SESSION['role']==="professor"){
  $user = login_session_professor($connection);
  $professor = $user['Professor_User_ID'];
    $query = "SELECT DISTINCT

  prof.Professor_User_ID,
              CASE WHEN trimelis_professors.prof_position = 1 THEN 'Επιβλέπων' ELSE 'Μέλος Τριμελούς' END AS professor_role,
              COUNT(DISTINCT thesis.Thesis_ID) AS total_theses,
              AVG(DATEDIFF(rd.ready_date, ad.active_date)) AS avg_days_active_to_ready,
              AVG(trimelis_vathmologia.Trimelis_Final_Grade) AS avg_mark
            FROM Professor prof
            LEFT JOIN (
              SELECT Thesis_ID, Trimelis_Professor_1 AS Professor_User_ID, 1 AS prof_position FROM trimelis
              UNION SELECT Thesis_ID, Trimelis_Professor_2, 2 FROM trimelis
              UNION SELECT Thesis_ID, Trimelis_Professor_3, 3 FROM trimelis
            ) trimelis_professors ON trimelis_professors.Professor_User_ID = prof.Professor_User_ID
            INNER JOIN trimelis_vathmologia ON trimelis_vathmologia.Thesis_ID = trimelis_professors.Thesis_ID
            INNER JOIN (SELECT Thesis_ID, Thesis_Status FROM thesis WHERE Thesis_Status='ready' AND Thesis_Final_Grade IS NOT NULL) thesis
              ON thesis.Thesis_ID = trimelis_professors.Thesis_ID
            INNER JOIN (SELECT Thesis_ID, MAX(Thesis_Date) AS active_date FROM thesis_date WHERE Thesis_Status='active' GROUP BY Thesis_ID) ad
              ON ad.Thesis_ID = thesis.Thesis_ID
            INNER JOIN (SELECT Thesis_ID, MAX(Thesis_Date) AS ready_date FROM thesis_date WHERE Thesis_Status='ready' GROUP BY Thesis_ID) rd
              ON rd.Thesis_ID = thesis.Thesis_ID
            WHERE prof.Professor_User_ID = $professor
            GROUP BY prof.Professor_User_ID, professor_role
            ORDER BY prof.Professor_User_ID, professor_role DESC
          ";

  $result = $connection->query($query);
  while ($row = $result->fetch_assoc()) {
    // Chart 1
    $labels_chart_1[] = $row['professor_role'];
    $values_chart_1[] = (float)$row['total_theses'];
    // Chart 2
    $labels_chart_2[] = $row['professor_role'];
    $values_chart_2[] = (float)$row['avg_days_active_to_ready'];
    // Chart 3 (ΔΙΟΡΘΩΜΕΝΟ)
    $labels_chart_3[] = $row['professor_role'];
    $values_chart_3[] = (float)$row['avg_mark'];
  }
} else {
  echo "<script>alert('You do not have permission to access this page.'); history.back();</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Προβολή Στατιστικών</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* Κάνει τα charts να προσαρμόζονται στο διαθέσιμο ύψος */
    .chart-wrap { height: 400px; }
    @media (max-width: 767.98px) { .chart-wrap { height: 320px; } }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <button class="btn btn-outline-light me-2 d-md-none"
              type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
              aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle sidebar">
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
      <?php include "sidebar.php"; ?>

    <main class="col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4 pt-4">
      <div class="card shadow-lg p-4">
        <h1 class="text-center fw-bold">Προβολή Στατιστικών</h1>
        <p class="lead">Στις πιο κάτω γραφικές παραστάσεις έχουμε:</p>

        <div class="row g-3">
          <!-- Chart 1 -->
          <div class="col-12 col-md-4">
            <div class="card shadow h-100">
              <div class="card-header text-center bg-primary text-white">
                Πλήθος διπλωματικών (Επίβλεψη / Τριμελής)
              </div>
              <div class="card-body chart-wrap">
                <canvas id="chart_total_theses"></canvas>
              </div>
            </div>
          </div>

          <!-- Chart 2 -->
          <div class="col-12 col-md-4">
            <div class="card shadow h-100">
              <div class="card-header text-center bg-primary text-white">
                Μέσος χρόνος περάτωσης (ημέρες)
              </div>
              <div class="card-body chart-wrap">
                <canvas id="myBarChart"></canvas>
              </div>
            </div>
          </div>

          <!-- Chart 3 -->
          <div class="col-12 col-md-4">
            <div class="card shadow h-100">
              <div class="card-header text-center bg-primary text-white">
                Μέσος βαθμός
              </div>
              <div class="card-body chart-wrap">
                <canvas id="avg_mark"></canvas>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Charts -->
  <script>
    // Chart 1
    const labels_chart_1 = <?php echo json_encode($labels_chart_1, JSON_UNESCAPED_UNICODE); ?>;
    const values_chart_1 = <?php echo json_encode($values_chart_1); ?>;
    new Chart(document.getElementById('chart_total_theses'), {
      type: 'bar',
      data: { labels: labels_chart_1, datasets: [{ label: 'Πλήθος', data: values_chart_1 }] },
      options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });

    // Chart 2
    const labels_chart_2 = <?php echo json_encode($labels_chart_2, JSON_UNESCAPED_UNICODE); ?>;
    const values_chart_2 = <?php echo json_encode($values_chart_2); ?>;
    new Chart(document.getElementById('myBarChart'), {
      type: 'bar',
      data: { labels: labels_chart_2, datasets: [{ label: 'Χρόνος (ημ.)', data: values_chart_2 }] },
      options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });

    // Chart 3
    const labels_chart_3 = <?php echo json_encode($labels_chart_3, JSON_UNESCAPED_UNICODE); ?>;
    const values_chart_3 = <?php echo json_encode($values_chart_3); ?>;
    new Chart(document.getElementById('avg_mark'), {
      type: 'bar',
      data: { labels: labels_chart_3, datasets: [{ label: 'Βαθμός', data: values_chart_3 }] },
      options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, suggestedMax: 10 } } }
    });
  </script>
</body>
</html>