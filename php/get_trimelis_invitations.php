<?php

session_start();

include("database_connection.php");
include("functions.php");

if (isset($_SESSION['role']) && ($_SESSION['role'] === "professor")){
    
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $am = $_POST['student_am'];
        $sql = "SELECT Thesis_ID FROM thesis WHERE Thesis_Student = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "i", $student_am);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row   = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($row) {
            $thesis_number = $row['Thesis_ID'];
            $sql2 = "SELECT * FROM trimelous_invitiation WHERE Thesis_ID = ?"; //ORDER BY date (prepi na prostheso date sto database)
            $stmt2 = mysqli_prepare($connection, $sql2);
            mysqli_stmt_bind_param($stmt2, "i", $thesis_number);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

        //    echo "Arithmos Pistopoiitikou Thesis Submit.";
        
           $message = '<div class="alert alert-success text-center">Thesis ID Found!</div>';

        } else {
            $message = '<div class="alert alert-success text-center"> No thesis found for this student!</div>';

            // echo "You can only submit ap to active thesis!";
        }
        

        header("refresh:5; url=./secretary_page.php");
        // exit;
    }
} else {
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
    <title>Πληροφορίες για προσκλήσεις τριμελής επιτροπής</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-custom {
            max-width: 800px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Η Πλατφόρμα</a>
        <a href="professor_page.php" class="btn btn-success">Αρχική Σελίδα</a>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-danger">Αποσύνδεση</a>
        </div>
    </div>
</nav>

<div class="container container-custom">
    <div id="Create_Diplomatiki" class="card shadow-lg p-4">
        <h2 class="mb-4 text-center text-primary">ΑΜ φοιτητή Διπλωματικής Εργασίας</h2>

        <form method="POST" action="./create_thesis.php" enctype="multipart/form-data">

           <div class="mb-3">
            <label for="student_am" class="form-label">Αριθμός Μητρώου:</label>
            <input type="text" class="form-control" id="student_am" name="student_am" required>
           </div>

            <div class="d-grid mt-4">
                <button type="submit" id="am_thesis" name="am_thesis" class="btn btn-primary btn-lg">
                    Αναζήτηση
                </button>

            </div>
        </form>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

