    <?php

    session_start();

    include("database_connection.php");
    include("functions.php");

    $user = login_session_student($connection);
    $_SESSION['username'] = $user['Student_name'] . " " . $user['Student_surname'];
    $_SESSION['email'] = $user['Student_email'] ;

    if (isset($_SESSION['role']) && ($_SESSION['role'] === "student")){

        if($_SERVER['REQUEST_METHOD'] == "POST")
        {   
            $student_am = $user['Student_number'];
            $address = !empty($_POST['address']) ? $_POST['address'] : $user['Student_street'];
            $address_number = !empty($_POST['address_number']) ? $_POST['address_number'] : $user['Student_street_number'];
            $address_city = !empty($_POST['address_city']) ? $_POST['address_city'] : $user['Student_city'];
            $email = !empty($_POST['email']) ? $_POST['email'] : $user['Student_email'];
            $mobile = !empty($_POST['phone_mobile']) ? $_POST['phone_mobile'] : $user['Student_mobile'];
            $landline = !empty($_POST['phone_landline']) ? $_POST['phone_landline'] : $user['Student_landline'];
            
            //Update profile data
            $query = "UPDATE student SET Student_street='$address', Student_street_number='$address_number', 
            Student_city='$address_city', Student_email='$email', Student_mobile='$mobile', Student_landline='$landline'
            WHERE Student_number='$student_am'";
            if (mysqli_query($connection, $query)) {
                if (mysqli_affected_rows($connection) > 0) {
                $message = '<div class="alert alert-success text-center"> Επιχτυημένη Προσπάθεια Ενημέρωσης Λογαριασμού!</div>';
            } else {
                $message = '<div class="alert alert-warning text-center">Δεν υπάρχουν αλλαγές!</div>';
            }

            }
        //Redirect after 5 seconds to the student page
        header("refresh:5; url=./student_page.php");
        }
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Επεξεργασία Προφίλ Φοιτητή</title>
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
      <div class="card shadow-lg p-4">
        <h2 class="mb-4 text-center text-primary">Επεξεργασία Προφίλ Φοιτητή</h2>

         <?php
    if (!empty($message)) {
        echo $message;
    }
?>
 <form method="POST" action="student_profile_edit.php">

            <div class="mb-3">
                <label class="form-label fw-bold">Διεύθυνση:</label>
                <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($user['Student_street'])?>">            
            </div>

            <div class="mb-3">
                 <label class="form-label fw-bold">Αριθμός Διεύθυνσης:</label>
                 <input type="text" class="form-control" name="address_number" value="<?php echo htmlspecialchars($user['Student_street_number'])?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Πόλη:</label>
                <input type="text" class="form-control" name="address_city" value="<?php echo htmlspecialchars($user['Student_city'])?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Ηλεκτρονική Διεύθυνση:</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['Student_email'])?>">     
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Κινητό Τηλέφωνο:</label>
                <input type="text" class="form-control" name="phone_mobile" value="<?php echo htmlspecialchars($user['Student_mobile'])?>">      
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Σταθερό Τηλέφωνο:</label>
                <input type="text" class="form-control" name="phone_landline" value="<?php echo htmlspecialchars($user['Student_landline'])?>">  
            </div>

            <div class="d-grid mt-4">
                <button type="submit" id="update_profile" name="update_profile" class="btn btn-primary btn-lg">
                    Ενημέρωση
                </button>
            </div>
        </form>

            </div>
</div>
</div>
</main>
</body></body>
</html>
