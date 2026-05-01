<?php
  session_Start();
  include("database_connection.php");
  include("functions.php");
  

  if(isset($_SESSION['role'])  && ($_SESSION['role'] === "professor")){
    $user=login_session_professor($connection);
    $epimelitis =[];
    $melos_trimelous =[];

    $prof_id = $user['Professor_User_ID'];
    $query = "SELECT * FROM trimelis WHERE Trimelis_Professor_1 = ? || Trimelis_Professor_2 = ? || Trimelis_Professor_3 = ? ";
    $stmt  = $connection->prepare($query);
    $stmt ->bind_param("iii",$prof_id,$prof_id,$prof_id);
    $stmt->execute();
    $result = $stmt ->get_result();
    $Thesis_id_all = [];
    if($result->num_rows === 0){
        echo "Δεν υπαρχουν διπλωματικες!";
        exit;
    }
    else{
        
    
        while ($row = $result ->fetch_assoc() ){
             if((int)$row['Trimelis_Professor_1'] == $prof_id){
                $epimelitis[] = $row ;
                $Thesis_id_all[]=$row['Thesis_ID'];
             }elseif((int)$row['Trimelis_Professor_2'] == $prof_id || (int)$row['Trimelis_Professor_3'] == $prof_id){
               $melos_trimelous[]= $row;
                $Thesis_id_all[]=$row['Thesis_ID'];

             }
             else{
                echo "something went wrong";
             }
}
    }
    $Thesis_details=[];
    foreach($Thesis_id_all as $row){
        $query  = "SELECT * FROM thesis WHERE Thesis_ID = ?";
        $stmt = $connection ->prepare($query);
        $stmt ->bind_param("i",$row);
        $stmt ->execute();
        $results = $stmt->get_result();
        if ($row = $results->fetch_assoc()) {
        $Thesis_details[] = $row;  // store the row, not the result object
    }
    }


    $xronologio = [];
    foreach($Thesis_id_all as $row){
      $query = "SELECT * FROM thesis_date WHERE Thesis_ID = ?";
      $stmt =$connection -> prepare($query);
      $stmt ->bind_param("i",$row);
      $stmt ->execute();
      $result=$stmt ->get_result();
      if($row = $result ->fetch_assoc()){
        $xronologio[]=$row;
      }
    }
     $invitation=[];
     foreach($Thesis_id_all as $row){
      $query = "SELECT * FROM trimelous_invitation WHERE Thesis_ID = ?";
      $stmt =$connection -> prepare($query);
      $stmt ->bind_param("i",$row);
      $stmt ->execute();
      $result=$stmt ->get_result();
      if($row = $result ->fetch_assoc()){
        $invitation[]=$row;
      }
    }
   
  
    $final_json = [
         "invitation" => $invitation,
         "xronologio" => $xronologio,
         "epimelitis" => $epimelitis,
         "melos_trimelous" => $melos_trimelous,
         "thesis_details" => $Thesis_details
    ];

    header('Content-Type: application/json');
    echo json_encode($final_json);



    

 
  }else{

    header("Location: login.php");
    exit;
  }
  
?>
