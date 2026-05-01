<?php 
session_start();
include("database_connection.php");
include("functions.php");
header("Content-Type: application/json");

$user = login_session_professor($connection);

if (isset($_SESSION['role']) && $_SESSION['role'] === "professor") {
    $inputJSON = file_get_contents("php://input");
    $data = json_decode($inputJSON, true);

    if (!isset($data["accept"]) || !isset($data["deny"]) || !isset($data["Id"]) || !is_numeric($data["Id"])) {
        echo json_encode(["error" => "Invalid input data"]);
        exit;
    }

    $accept = $data["accept"];
    $deny = $data["deny"];
    $T_id = $data["Id"];
    $professor_id = $user['Professor_User_ID'];

    if ($accept) {

        $updateInvitation = $connection->prepare("UPDATE trimelous_invitation SET Invitation_Status = 'accept', Trimelous_Date = NOW() WHERE Professor_User_ID = ? AND Thesis_ID = ?");
        $updateInvitation->bind_param("ii", $professor_id, $T_id);
        if ($updateInvitation->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Database error: " . $updateInvitation->error]);
        }
        $updateInvitation->close();

        //Check current professor slots
        $stmt = $connection->prepare("SELECT trimelis_Professor_2, trimelis_Professor_3 FROM trimelis WHERE Thesis_ID = ?");
        $stmt->bind_param("i", $T_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $prof2 = $row['trimelis_Professor_2'];
            $prof3 = $row['trimelis_Professor_3'];
            $stmt->close();

            if (is_null($prof2)) {
                // Set as Professor 2
                $updateProf = $connection->prepare("UPDATE trimelis SET Trimelis_Professor_2 = ? WHERE Thesis_ID = ?");
                $updateProf->bind_param("ii", $professor_id, $T_id);
                $updateProf->execute();
                $updateProf->close();
            } elseif (is_null($prof3)) {
                // Set as Professor 3
                $updateProf = $connection->prepare("UPDATE trimelis SET Trimelis_Professor_3 = ? WHERE Thesis_ID = ?");
                $updateProf->bind_param("ii", $professor_id, $T_id);
                $updateProf->execute();
                $updateProf->close();
                
                //This needs to be here not in else
                // Both slots are taken, cancel all invitations
                $cancelAll = $connection->prepare("UPDATE trimelous_invitation SET Invitation_Status = 'cancel' WHERE Thesis_ID = ? AND Invitation_Status = 'pending'");
                $cancelAll->bind_param("i", $T_id);
                $cancelAll->execute();
                $cancelAll->close();
                $updateActive = $connection -> prepare ("UPDATE thesis SET Thesis_Status = 'active' WHERE Thesis_ID = ? ");
                $updateActive -> bind_param("i",$T_id);
                $updateActive ->execute();
                $updateActive -> close();
                $updateActiveDate = $connection -> prepare ("INSERT INTO thesis_date (Thesis_ID, Thesis_Date, Thesis_Status) VALUES (?, NOW(), 'active')");
                $updateActiveDate -> bind_param("i",$T_id);
                $updateActiveDate ->execute();
                $updateActiveDate -> close();

                echo json_encode(["error" => "All professor slots are filled. Invitations cancelled."]);
            } /*else {
                // Both slots are taken, cancel all invitations
                $cancelAll = $connection->prepare("UPDATE trimelous_invitation SET Invitation_Status = 'cancel' WHERE Thesis_ID = ?");
                $cancelAll->bind_param("i", $T_id);
                $cancelAll->execute();
                $cancelAll->close();
                $updateActive = $connection -> prepare ("UPDATE thesis SET Thesis_Status = 'active' WHERE Thesis_ID = ? ");
                $updateActive -> bind_param("i",$T_id);
                $updateActive ->execute();
                $updateActive -> close();
                $updateActiveDate = $connection -> prepare ("INSERT INTO thesis_date (Thesis_ID, Thesis_Date, Thesis_Status) VALUES (?, NOW(), 'active')");
                $updateActiveDate -> bind_param("i",$T_id);
                $updateActiveDate ->execute();
                $updateActiveDate -> close();

                echo json_encode(["error" => "All professor slots are filled. Invitations cancelled."]);
                exit;
            }*/

            // Now, update the invitation status to "accept"
            
        } else {
            echo json_encode(["error" => "Thesis not found"]);
            exit;
        }
    } elseif ($deny) {
        // Just cancel this professor's invitation
        $updateInvitation = $connection->prepare("UPDATE trimelous_invitation SET Invitation_Status = 'deny', Trimelous_Date = NOW() WHERE Professor_User_ID = ? AND Thesis_ID = ?");
        $updateInvitation->bind_param("ii", $professor_id, $T_id);
        if ($updateInvitation->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Database error: " . $updateInvitation->error]);
        }
        $updateInvitation->close();
    } else {
        echo json_encode(["error" => "Invalid action"]);
        exit;
    }
} else {
    echo json_encode(["error" => "Unauthorized access"]);
    exit;
}
?>
