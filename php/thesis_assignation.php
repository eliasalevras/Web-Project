<?php

session_start();

include("database_connection.php");
include("functions.php");

$user = login_session_professor($connection);
$_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
$_SESSION['email'] = $user['Professor_email'] ;

if (isset($_SESSION['role']) && ($_SESSION['role'] === "professor")){
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $user = login_session_professor($connection);
        $epimelitis = $user['Professor_User_ID'];
        $status = 'pending';
        if (isset($_POST['assign_thesis_by_am'])) {
            $thesis_title = $_POST['thesis_title'];
            $student_am = $_POST['student_am'];
            //Check if student_am exists in thesis database
            $query = "SELECT Thesis_Title from thesis where Thesis_Student = '$student_am'";
            $result = mysqli_query($connection, $query);
            if (mysqli_num_rows($result) > 0)
            {
                $row = mysqli_fetch_assoc($result);
                $existing_thesis_title = $row['Thesis_Title'];
                $message = "<div class='alert alert-warning text-center'>Αυτός ο φοιτητής έχει ήδη θέμα διπλωματικής εργασίας με τον τίτλο $existing_thesis_title!</div>";

            }
            else 
            {   
                $query = "SELECT Student_number from student where Student_number = '$student_am'";
                $result = mysqli_query($connection, $query);
                if (mysqli_num_rows($result) > 0)
                {
                    //AM acceptable.
                    $query = "UPDATE thesis SET Thesis_Student='$student_am', Thesis_Status='$status' WHERE Thesis_Title='$thesis_title'";
                    if (mysqli_query($connection, $query)) {
                        if (mysqli_affected_rows($connection) > 0) {
                            $query = "SELECT Thesis_ID from thesis where Thesis_Student = '$student_am'";
                            $result = mysqli_query($connection, $query);
                            if (mysqli_num_rows($result) > 0)
                            {
                                $row = mysqli_fetch_assoc($result);
                                $thesis_number = $row['Thesis_ID'];
                            }
                            $sql3 = "INSERT INTO thesis_date (Thesis_ID, Thesis_Date, Thesis_Status)
                                    VALUES (?, NOW(), ?)";
                            $stmt3 = mysqli_prepare($connection, $sql3);
                            mysqli_stmt_bind_param($stmt3, "is", $thesis_number, $status);
                            mysqli_stmt_execute($stmt3);
                            mysqli_stmt_close($stmt3);

                            $message = "<div class='alert alert-success text-center'> Η ανάθεση της διπλωματικής εργασίας ολοκληρώθηκε με επιτυχία!</div>";


                        } else {

                            $message = "<div class='alert alert-danger text-center'> Ανεπιτυχής. Δεν υπάρχει τέτοιος τίτλος διπλωματικής εργασίας! </div>";


                        }
                        //Update trimelis professor 1
                        $query = "SELECT Thesis_ID, Thesis_Epimelitis FROM thesis where Thesis_Student = '$student_am'";
                        $result = mysqli_query($connection, $query);
                        if (mysqli_num_rows($result) > 0)
                        {
                            $row = mysqli_fetch_assoc($result);
                            $thesis_id = $row['Thesis_ID'];
                            $thesis_epimelitis = $row['Thesis_Epimelitis'];
                            //Thesis ID and Epimelitis Found
                            $query = "INSERT INTO trimelis (Thesis_ID, Trimelis_Professor_1) VALUES ('$thesis_id', '$thesis_epimelitis')";
                            if (mysqli_query($connection, $query)) {
                                if (mysqli_affected_rows($connection) > 0) {
                                    $message = "<div class='alert alert-success text-center'> Επιτυχημένη Ανάθεση Διπλωματικής Εργασίας! </div>";

                                } else {
                                    $message = "<div class='alert alert-danger text-center'> Ανεπιτυχής. Δεν υπάρχει τέτοια διπλωματική ή φοιτητή διπλωματικής.</div>";
                                    
                                }
                            }
                        }
                    } else {
                        $message = "<div class='alert alert-warning text-center'> Σφάλμα κατά την ανάθεση διπλωματικής εργασίας σε φοιτητή: " . mysqli_error($connection) . " </div>";


                    }
                }
                else {
                    $message = "<div class='alert alert-danger text-center'> Δεν υπάρχει φοιτητής με Αριθμό Μητρώου:$student_am! " . mysqli_error($connection) . " </div>";

                }
            }
        } elseif (isset($_POST['assign_thesis_by_name_lastname'])) {
            $thesis_title = $_POST['thesis_title'];
            $student_name = $_POST['student_name'];
            $student_lastname = $_POST['student_lastname'];

            //Find Student AM based on name and lastname
            $query = "SELECT Student_number FROM student WHERE Student_name='$student_name' AND Student_surname='$student_lastname'";
            $result = mysqli_query($connection, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $student_am = $row['Student_number'];

                //Check if student_am exists in thesis database
                $query = "SELECT Thesis_Title FROM thesis WHERE Thesis_Student = '$student_am'";
                $result = mysqli_query($connection, $query);
                if (mysqli_num_rows($result) > 0)
                {
                    $row = mysqli_fetch_assoc($result);
                    $existing_thesis_title = $row['Thesis_Title'];
                    
                    $message = "<div class='alert alert-warning text-center'> Αυτός ο φοιτητής έχει ήδη θέμα διπλωματικής εργασίας με τον τίτλο $existing_thesis_title! </div>";

                }
                else 
                {   
                    //AM acceptable. 
                    $query = "UPDATE thesis SET Thesis_Student='$student_am', Thesis_Status='$status' WHERE Thesis_Title='$thesis_title'";
                    if (mysqli_query($connection, $query)) {
                        if (mysqli_affected_rows($connection) > 0) {
                             $query = "SELECT Thesis_ID from thesis where Thesis_Student = '$student_am'";
                            $result = mysqli_query($connection, $query);
                            if (mysqli_num_rows($result) > 0)
                            {
                                $row = mysqli_fetch_assoc($result);
                                $thesis_number = $row['Thesis_ID'];
                            }
                            $sql3 = "INSERT INTO thesis_date (Thesis_ID, Thesis_Date, Thesis_Status)
                                    VALUES (?, NOW(), ?)";
                            $stmt3 = mysqli_prepare($connection, $sql3);
                            mysqli_stmt_bind_param($stmt3, "is", $thesis_number, $status);
                            mysqli_stmt_execute($stmt3);
                            mysqli_stmt_close($stmt3);

                            $message = "<div class='alert alert-success text-center'> Επιτυχημένη Ανάθεση Διπλωματικής Εργασίας! </div>";


                        } else {
                            
                            $message = "<div class='alert alert-danger text-center'>Ανεπιτυχής. Δεν υπάρχει τέτοιος τίτλος διπλωματικής εργασίας!</div>";

                            // echo "Unsuccesful. There is no such Thesis Title!";
                        }
                        //Update trimelis professor 1
                        $query = "SELECT Thesis_ID, Thesis_Epimelitis FROM thesis WHERE Thesis_Student = '$student_am'";
                        $result = mysqli_query($connection, $query);
                        if (mysqli_num_rows($result) > 0)
                        {
                            $row = mysqli_fetch_assoc($result);
                            $thesis_id = $row['Thesis_ID'];
                            $thesis_epimelitis = $row['Thesis_Epimelitis'];
                            //Thesis ID and Epimelitis Found
                            $query = "INSERT INTO trimelis (Thesis_ID, Trimelis_Professor_1) VALUES ('$thesis_id', '$thesis_epimelitis')";
                            if (mysqli_query($connection, $query)) {
                                if (mysqli_affected_rows($connection) > 0) {
                                                                        
                                    $message = "<div class='alert alert-success text-center'> Πετυχημένη Ανάληψη Διπλωματικής! </div>";
                                    
                                } else {
                                                                    
                                    $message = "<div class='alert alert-danger text-center'> Ανεπιτυχής. Δεν υπάρχει τέτοια διπλωματική ή φοιτητή διπλωματικής. </div>";
                                    
                                }
                            }
                        }
                    } else {
                        
                        $message = "<div class='alert alert-warning text-center'> Σφάλμα κατά την ανάθεση διπλωματικής εργασίας σε φοιτητή: " . mysqli_error($connection) . " </div>";

                        echo "Error with assigning thesis to a student: " . mysqli_error($connection);
                    }
                    }
            }
            else {
                
                $message = "<div class='alert alert-danger text-center'> Δεν υπάρχει φοιτητής με όνομα: $student_name $student_lastname ! </div>";
                            
            }
        }
        //Redirect after 3 seconds to the professor page
        // header("refresh:5; url=./professor_page.php");
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
    <title>Ανάθεση Διπλωματικής Εργασίας</title>
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

    <!-- Main περιεχόμενο -->
    <main class="col-md-8 col-lg-9 ms-sm-auto px-3 px-md-4 pt-4">
      <h3 class="mb-4 text-center text-primary">Επιλέξτε Φόρμα Ανάθεσης Διπλωματικής Εργασίας</h3>

           <?php
    if (!empty($message)) {
        echo $message;
    }
    ?>
<div class="mb-3">
    <label for="formSelector" class="form-label">Επιλογή:</label>
    <select id="formSelector" class="form-select">
        <option value="">-- Επιλέξτε --</option>
        <option value="anathesi_id">Ανάθεση Διπλωματικής Εργασίας με ΑΜ</option>
        <option value="anathesi_name">Ανάθεση Διπλωματικής Εργασίας με Ονοματεπώνυμο</option>
    </select>
</div>


<!-- Επιλογή 1 με ΑΜ -->
<form id="anathesi_id" method="POST" action="thesis_assignation.php" class="border p-3 rounded bg-light d-none">
    <h5 class="mb-4 text-center text-primary">Ανάθεση Διπλωματικής με ΑΜ:</h5>   
    <div class="mb-3">
        <label for="thesis_title" class="form-label">Τίτλος Διπλωματικής:</label>
        <input type="text" class="form-control" id="thesis_title" name="thesis_title" placeholder="Πληκτρολογήστε τον τίτλο της Διπλωματικής Εργασίας..." required>
    </div>
    <div class="mb-3">
        <label for="student_am" class="form-label">Εισαγάγετε τον Αριθμό Μητρώου για να αναθέσετε αυτήν την εργασία</label>
        <input type="text" class="form-control" id="student_am" name="student_am" placeholder="Πληκτρολογήστε τον Αριθμό Μητρώου Φοιτητή..." required>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary px-5" id="assign_thesis_by_am" name="assign_thesis_by_am">Ανάθεση</button>
    </div>
</form>

<!-- Επιλογή 2 με Ονοματεπώνυμο -->
<form id="anathesi_name" method="POST" action="thesis_assignation.php" class="border p-3 rounded bg-light d-none">
    <h5 class="mb-4 text-center text-primary">Ανάθεση Διπλωματικής με Ονοματεπώνυμο:</h5>   
    <div class="mb-3">
        <label for="thesis_title" class="form-label">Τίτλος Διπλωματικής Εργασίας:</label>
        <input type="text" class="form-control" id="thesis_title" name="thesis_title"  placeholder="Πληκτρολογήστε τον τίτλο της Διπλωματικής Εργασίας..." required>
    </div>
    <div class="mb-3">
        <label for="student_name" class="form-label">Όνομα Φοιτητή:</label>
        <input type="text" class="form-control" id="student_name" name="student_name"  placeholder="Πληκτρολογήστε το Όνομα Φοιτητή" required>
    </div>
    <div class="mb-3">
        <label for="student_lastname" class="form-label">Επώνυμο Φοιτητή</label>
        <input type="text" class="form-control" id="student_lastname" name="student_lastname" placeholder="Πληκτρολογήστε το Επώνυμο Φοιτητή" required>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary px-5" id="assign_thesis_by_name_lastname" name="assign_thesis_by_name_lastname">Ανάθεση</button>
    </div>
</form>


<script>
document.getElementById('formSelector').addEventListener('change', function() {
    // Κρύβουμε όλα τα forms που αφορούν την επιλογή
    document.getElementById('anathesi_id').classList.add('d-none');
    document.getElementById('anathesi_name').classList.add('d-none');
    
    // Εμφανίζουμε το επιλεγμένο
    if (this.value) {
        document.getElementById(this.value).classList.remove('d-none');
    }
});

</script>
</body>
</html>
