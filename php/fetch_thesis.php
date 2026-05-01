<?php

session_start();

include("database_connection.php");
include("functions.php");

$thesis_data=[];

if (isset($_SESSION['role'])){


 if (($_SESSION['role'] === "secretary")) {
    $query = "SELECT thesis.Thesis_ID, thesis.Thesis_Title, thesis.Thesis_Description, thesis.Thesis_Status, professor.Professor_name, professor.Professor_surname,
              student.Student_name, student.Student_surname, thesis.Thesis_Final_Grade ,
              trimelis.*, tad.Thesis_Date,
              CONCAT(p1.Professor_name,' ',p1.Professor_surname) as Professor1,
              CONCAT(p2.Professor_name,' ',p2.Professor_surname) as Professor2,
              CONCAT(p3.Professor_name,' ',p3.Professor_surname) as Professor3,
              DATEDIFF(CURDATE(), tad.Thesis_Date) AS Days_Passed
              FROM thesis 
              INNER JOIN professor ON Thesis_Epimelitis = Professor_User_ID 
              INNER JOIN trimelis ON thesis.Thesis_ID =trimelis.Thesis_ID
              INNER JOIN student ON Thesis_Student = Student_Number 
              LEFT  JOIN professor p1 ON p1.Professor_User_ID = trimelis.Trimelis_Professor_1
              LEFT  JOIN professor p2 ON p2.Professor_User_ID = trimelis.Trimelis_Professor_2
              LEFT  JOIN professor p3 ON p3.Professor_User_ID = trimelis.Trimelis_Professor_3
              LEFT JOIN (SELECT Thesis_ID, Thesis_Date FROM thesis_date 
              WHERE Thesis_Status = 'active') tad ON tad.Thesis_ID = thesis.Thesis_ID
              WHERE thesis.Thesis_Status = 'active' OR thesis.Thesis_Status = 'under_review'"; 

$result = $connection ->query($query);
// $stmt = $connection->prepare($query);
//     if (!$stmt) {
//       die("Prepare failed: " . $connection->error);
//      }
     
//     $stmt->bind_param("iii",  $Thesis_ID, $epimelitis, $epimelitis);
//     $stmt->execute();
//     $result = $stmt->get_result();



    ?>
    <div class="card shadow-sm mb-4">
      <div class="card-header"><strong>Λίστα Διπλωματικών Εργασιών</strong></div>
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead>
            <tr>
              <th>Τίτλος</th>
              <th>Περιγραφή</th>
              <th>Κατάσταση</th>
              <th>Φοιτητής</th>
              <th>Επιμελητής</th>
              <th>Μελος τριμελούς</th>
              <th>Μελος τριμελούς</th>
              <th>Μέρες απο ανάθεση</th>
              <th>Τελικός Βαθμός</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['Thesis_Title']) ?></td>
                  <td><?= nl2br(htmlspecialchars($row['Thesis_Description'])) ?></td>
                  <td><?= htmlspecialchars($row['Thesis_Status']) ?></td>
                  <td><?= htmlspecialchars($row['Student_name']) . " " . htmlspecialchars($row['Student_surname']) ?></td>
                  <td><?= htmlspecialchars($row['Professor1']) ?></td>
                  <td><?= htmlspecialchars($row['Professor2']) ?></td>
                  <td><?= htmlspecialchars($row['Professor3']) ?></td>
                  <td><?= htmlspecialchars($row['Days_Passed']) ?></td>
                  <td><?= $row['Thesis_Final_Grade'] ? htmlspecialchars($row['Thesis_Final_Grade']) : "-" ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="7" class="text-center text-muted">Δεν βρέθηκαν πτυχιακές.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
}



    else if (($_SESSION['role'] === "student")) {

        $user = login_session_student($connection);
        $student_am = $user['Student_number'];
        $student_name = $user['Student_name'];
        $student_lastname = $user['Student_surname'];
        
        $query = " SELECT thesis.Thesis_ID, thesis.Thesis_Title, thesis.Thesis_Description, thesis.Thesis_Status, professor.Professor_name, professor.Professor_surname, thesis.Thesis_Final_Grade,
        trimelis.Trimelis_Professor_1, trimelis.Trimelis_Professor_2, trimelis.Trimelis_Professor_3, tad.Thesis_Date,
        DATEDIFF(CURDATE(), tad.Thesis_Date) AS Days_Passed,
        CONCAT(p1.Professor_name,' ',p1.Professor_surname) as Professor1,
        CONCAT(p2.Professor_name,' ',p2.Professor_surname) as Professor2,
        CONCAT(p3.Professor_name,' ',p3.Professor_surname) as Professor3
        FROM thesis INNER JOIN professor ON thesis.Thesis_Epimelitis = professor.Professor_User_ID 
        LEFT JOIN trimelis ON thesis.Thesis_ID = trimelis.Thesis_ID 
        LEFT JOIN professor p1 ON p1.Professor_User_ID = trimelis.Trimelis_Professor_1
        LEFT JOIN professor p2 ON p2.Professor_User_ID = trimelis.Trimelis_Professor_2
        LEFT JOIN professor p3 ON p3.Professor_User_ID = trimelis.Trimelis_Professor_3
        LEFT JOIN (SELECT Thesis_ID, Thesis_Date FROM thesis_date 
        WHERE Thesis_Status = 'active') tad ON tad.Thesis_ID = thesis.Thesis_ID
        WHERE Thesis_Student = $student_am";
        $result = $connection->query($query);
        //LEFT JOIN thesis_date ON thesis.Thesis_ID = thesis_date.Thesis_ID AND thesis_date.Thesis_Status='active'
        ?>
    
        <div class="card shadow-sm mb-4">
      <div class="card-header"><strong>Διπλωματική Εργασία Φοιτητή</strong></div>
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead>
            <tr>
              <th>Τίτλος</th>
              <th>Περιγραφή</th>
              <th>Κατάσταση</th>
              <th>Τελικός Βαθμός</th>
              <th>Μέρες απο ανάθεση</th>
              <th>Επιμελητής</th>
              <th>Μελος τριμελούς</th>
              <th>Μελος τριμελούς</th>
              

            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['Thesis_Title']) ?></td>
                  <td><?= nl2br(htmlspecialchars($row['Thesis_Description'])) ?></td>
                  <td><?= htmlspecialchars($row['Thesis_Status']) ?></td>
                  <td><?= $row['Thesis_Final_Grade'] ? htmlspecialchars($row['Thesis_Final_Grade']) : "-" ?></td>
                  <td><?= htmlspecialchars($row['Days_Passed']) ?></td>
                  <td><?= htmlspecialchars($row['Professor1']) ?></td>
                  <td><?= htmlspecialchars($row['Professor2']) ?></td>
                  <td><?= htmlspecialchars($row['Professor3']) ?></td>

                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="7" class="text-center text-muted">Δεν έχετε διπλωματική εργασία.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
    }











    //echo json_encode($thesis_data);
    //exit;
}
else {
    //Redirect to previous page (No access if role is not professor)
    //header('Location: ' . $_SERVER['HTTP_REFERER']);
    // Output JavaScript to redirect back
    echo '<script type="text/javascript">
            alert("You do not have permission to access this page.");
            history.back();
          </script>';
    exit;
}
?>