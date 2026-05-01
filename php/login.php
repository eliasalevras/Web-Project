
<?php
    session_start();
    //Ginete to connection me database kai meso tou functions gnorizi pios user ine sindedemenos.
    include("database_connection.php");
    include("functions.php");

    //Me Post pernis ta stoixeia tis formas. Pio asfales apo get afou me get fenontai ta stoixia sto URL.
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {   //Dimiourgia php metavliton 
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * from user_info where User_Username = ?";
        $stmt = $connection->prepare($query);
        if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }
        $stmt->bind_param("s" , $username);
        $stmt->execute();
        $result=$stmt->get_result();
        //if result was executed successfull and rows > 1 (i kalo tha itan = 1 efoson den iparxi user me idia stixia)kane login
        if ($result && $result->num_rows === 1)
        {   //fetch entoli sql i opia metaferi stin metavliti $user ola ta apotelesmata tou query mas. Giafto meta mporoume na xrisimopiisoume px $user['password']
            $user = mysqli_fetch_assoc($result);
            //elexos an dothike sosto password
            if ($user['User_Password'] === $password)
            {   //Thetoume ta stixia sta session
                $_SESSION['User_ID'] = $user['User_ID'];
                $_SESSION['role'] = $user['User_Role'];
                //Elegxoume to role gia na kseroume se pio main page na katefthinthoume.
                if ($_SESSION['role'] === "professor")
                {
                    header("Location: professor_page.php");
                    exit;
                }
                elseif ($_SESSION['role'] === "student")
                {
                    header("Location: student_page.php");
                    exit;
                }
                elseif ($_SESSION['role'] === "secretary")
                {
                    header("Location: secretary_page.php");
                    exit;
                }
            }
            else{$error_message = "Λάθος Κωδικός Πρόσβασης! Προσπαθήστε ξανά.";

            }
        }
        else
        {
            $error_message = "Ο λογαριασμός δεν υπάρχει! Προσπαθήστε ξανά.";
        }
        
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Είσοδος</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column justify-content-center align-items-center min-vh-100">

    <div class="container text-center text-white mb-4">
        <h2 class="text-dark fw-bold">Καλώς ήρθατε στην πλατφόρμα!</h2>
        <p class="text-dark fw-bold">Παρακαλούμε συνδεθείτε για να συνεχίσετε.</p>
    </div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 rounded-4 overflow-hidden shadow">
                <div class="row g-0">
                    <!-- Left image -->
                    <div class="col-md-6 d-none d-md-block">
                        <img src="thesis.png" alt="Login Image" class="img-fluid h-100 w-100" style="object-fit: cover;">
                    </div>
                    <div class="col-md-6 p-5 bg-white">

                    <div class="text-center mb-4">
                            <h5 class="fw-bold">Συνδεθείτε στον λογαριασμό σας!</h5>
                        </div>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label small fw-bold">Username</label>
                                <input type="text" id="username" name="username" class="form-control form-control-sm" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label small fw-bold">Password</label>
                                <input type="password" id="password" name="password" class="form-control form-control-sm" required>
                            </div>
                            
                            <button type="submit" class="btn btn-dark w-100 fw-bold">Log In</button>

                               <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger text-center p-2 small">
                                <?= htmlspecialchars($error_message) ?>
                            </div>
                        <?php endif; ?>
                          <div class="mb-3"></div>
                                <div class="d-flex justify-content-center gap-2">
                                     <a href="main_page.php" class="btn btn-primary w-100 fw-bold">Ανακοινώσεις</a>
                                </div>
          


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




