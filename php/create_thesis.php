<?php
session_start();

include("database_connection.php");
include("functions.php");

// $_SESSION['email'] = $user['Professor_email'];

if (isset($_SESSION['role']) && ($_SESSION['role'] === "professor")) {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $title = $_POST['thesis_title'] ?? '';
        $description = $_POST['thesis_description'] ?? '';

        // Στοιχεία καθηγητή
        $user = login_session_professor($connection);
        $epimelitis = $user['Professor_User_ID'];
        
        $_SESSION['username'] = $user['Professor_name'] . " " . $user['Professor_surname'];
        $_SESSION['email']    = $user['Professor_email'];   
        // Uploads
        $upload_directory = "../uploads/";
        $pdf_name = $_FILES['thesis_pdf']['name'] ?? '';
        $pdf_tmp_name = $_FILES['thesis_pdf']['tmp_name'] ?? '';

        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0777, true);
        }

        if (!empty($pdf_name) && is_uploaded_file($pdf_tmp_name)) {
            $unique_pdf_name = uniqid("thesis_", true) . "_" . basename($pdf_name);
            $destination = $upload_directory . $unique_pdf_name;

            if (move_uploaded_file($pdf_tmp_name, $destination)) {
                $query = "INSERT INTO thesis (Thesis_Title, Thesis_Description, Thesis_PDF, Thesis_Epimelitis) 
                          VALUES ('$title', '$description', '$destination', '$epimelitis')";
                if (mysqli_query($connection, $query)) {
                    $_SESSION['success'] = "Η διπλωματική καταχωρήθηκε επιτυχώς!";
                } else {
                    $_SESSION['error'] = "Αποτυχία καταχώρησης: " . mysqli_error($connection);
                }
            } else {
                $_SESSION['error'] = "Απέτυχε η αποθήκευση του αρχείου.";
            }
        } else {
            // Αν δεν ανέβηκε αρχείο, μπορείς να επιλέξεις να επιτρέπεις καταχώρηση χωρίς PDF
            // ή να δώσεις λάθος:
            $_SESSION['error'] = "Δεν επιλέχθηκε έγκυρο αρχείο PDF.";
        }


        header("Location: ./form_create_diplomatiki.php");
        exit;

    } else {
        // Όχι POST
        echo '<script type="text/javascript">
                alert("You do not have permission to access this page.");
                history.back();
              </script>';
        exit;
    }

} else {
    // Όχι professor
    echo '<script type="text/javascript">
            alert("You do not have permission to access this page.");
            history.back();
          </script>';
    exit;
}
