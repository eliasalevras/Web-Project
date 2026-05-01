<?php
include("database_connection.php");

function url(){
    // Read the JSON file
    $jsonData = file_get_contents('http://usidas.ceid.upatras.gr/web/2024/export.php');

    // Parse the JSON data
    $data = json_decode($jsonData, true);

    json_to_sql($data);
}

function upload($file){
    // Read the JSON file
    //['tmp_name'] = The temporary filename of the file in which the uploaded file was stored on the server.
    $jsonData = file_get_contents($file['tmp_name']);

    // Parse the JSON data
    $data = json_decode($jsonData, true);

    json_to_sql($data);
}

//JSON File From URL
function json_to_sql($data){
    global $connection;

    $students_info = $data['students'];
    $professors_info = $data['professors'];
    $succesfull_status = false;
    $duplicated_users = true; //if it remains true it means no new data was inserted in sql.

    //Iterate through the JSON data and insert into the database
    foreach ($students_info as $students) {
        //$id_count++;//hash('sha256', uniqid(rand(), true)); // Generates a unique 64-character hash
        $name = $students['name'];
        $surname = $students['surname'];
        $student_number = $students['student_number'];
        $street = $students['street'];
        $number = $students['number'];
        $city = $students['city'];
        $postcode = $students['postcode'];
        $father_name = $students['father_name'];
        $landline_telephone = $students['landline_telephone'];
        $mobile_telephone = $students['mobile_telephone'];
        $email = $students['email'];
        $password = 1;//password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT); // Secure password

        //If user exists(duplicated) dont insert.
        $user_count = "SELECT User_ID FROM User_Info WHERE User_Username = '$email'";
        $result = $connection->query($user_count);

        if ($result->num_rows == 0) {
            //Insert info to User table
            $sql_user_info = "INSERT INTO User_Info (User_Username, User_Password, User_Role) VALUES ('$email', '$password', 'student')";

            //Execute the SQL statement
            if ($connection->query($sql_user_info) === TRUE) {
                //echo "Product inserted successfully. <br>";
                //Find User Id
                $user_id = $connection->insert_id;
                $duplicated_users = false;
                $succesfull_status = true;

                //Prepare the SQL statement
                $sql_students_data = "INSERT INTO Student (Student_number,Student_name,Student_surname,Student_street,Student_street_number,Student_city,Student_postcode,Student_father_name,Student_landline,Student_mobile,Student_email,Student_User_ID)
                VALUES ('$student_number', '$name', '$surname', '$street', '$number', '$city', '$postcode', '$father_name', '$landline_telephone', '$mobile_telephone', '$email', '$user_id')";    

                if ($connection->query($sql_students_data) === TRUE) {
                    //echo "Product inserted successfully. <br>";
                    $succesfull_status = true;
                } else {
                    //echo "Error inserting product: " . $connection->error . "<br>";
                    $succesfull_status = false;
                }
            }
            else {
                die("Error inserting user: " . $connection->error);
            }
        }
    }

    // Iterate through the JSON data and insert into the database
    foreach ($professors_info as $professor) {
        //$id_count++; //hash('sha256', uniqid(rand(), true)); // Generates a unique 64-character hash
        $name = $professor['name'];
        $surname = $professor['surname'];
        $email = $professor['email'];
        $topic = $professor['topic'];
        $landline = $professor['landline'];
        $mobile = $professor['mobile'];
        $department = $professor['department'];
        $university = $professor['university'];
        $password = 0;//password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT); // Secure password
        
         //If user exists(duplicated) dont insert.
         $user_count = "SELECT User_ID FROM User_Info WHERE User_Username = '$email'";
         $result = $connection->query($user_count);
 
        if ($result->num_rows == 0) {
            //Insert info to User table
            $sql_user_info = "INSERT INTO User_Info (User_Username, User_Password, User_Role) VALUES ('$email', '$password', 'professor')";

            // Execute the SQL statement
            if ($connection->query($sql_user_info) === TRUE) {
                //echo "Detail inserted successfully. <br>";
                //Find User Id
                $user_id = $connection->insert_id;
                $duplicated_users = false;
                $succesfull_status = true;
                
                //Prepare the SQL statement
                $sql_professors_data = "INSERT INTO Professor (Professor_User_ID, Professor_name, Professor_surname, Professor_email, Professor_topic, Professor_landline, Professor_mobile, Professor_department, Professor_university)
                VALUES ('$user_id', '$name', '$surname', '$email', '$topic', '$landline', '$mobile', '$department', '$university')";    

                
                if ($connection->query($sql_professors_data) === TRUE) {
                    //echo "Detail inserted successfully. <br>";
                    $succesfull_status = true;
                }
                else {
                    //echo "Error inserting detail: " . $connection->error . "<br>";
                    $succesfull_status = false;
                }
            }
            else {
                die("Error inserting user: " . $connection->error);
            }
        }
    }

    if ($succesfull_status){
        $_SESSION['status'] = "Json was succesfully loaded to SQL table";
        // echo "Json was succesfully loaded to SQL table . <br>";
    }
    else if ($duplicated_users) {
        $_SESSION['status'] = "No new users were inserted due to duplication";
        // echo "No new users were inserted due to duplication. <br>";
    }
    else{
        $_SESSION['status'] = "Json failed to load to database";
        // echo "Json failed to load to database . <br>";
    }
    // Close the database connection
       $connection->close();
}
?>