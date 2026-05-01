<?php

function login_session_professor($connection)
{
    //If already login
    if(isset($_SESSION['User_ID']))
    {
        $id = $_SESSION['User_ID'];
        $query = "select * from professor where Professor_User_ID = '$id'";
        $result = mysqli_query($connection, $query);
        if ($result && mysqli_num_rows($result) === 1)
        {
            $user = mysqli_fetch_assoc($result); //Fetch the result
            return $user;
        }
    }
    else
    {
        //Not already login -> goto login.php
        header("Location: login.php");
        exit;
    }
} 
    
function login_session_secretary($connection)
{
    //If already login
    if(isset($_SESSION['User_ID']))
    {
        $id = $_SESSION['User_ID'];
        $query = "select * from secretary where Secretary_User_ID = '$id'";
        $result = mysqli_query($connection, $query);
        if ($result && mysqli_num_rows($result) === 1)
        {
            $user = mysqli_fetch_assoc($result);
            return $user;
        }
    }
    else
    {
        //Not already login -> goto login.php
        header("Location: login.php");
        exit;
    }
}
        
function login_session_student($connection)
{
    //If already login
    if(isset($_SESSION['User_ID']))
    {
        $id = $_SESSION['User_ID'];
        $query = "select * from student where Student_User_ID = '$id'";
        $result = mysqli_query($connection, $query);
        if ($result && mysqli_num_rows($result) === 1)
        {
            $user = mysqli_fetch_assoc($result);
            return $user;
        }
    }
    else
    {
        //Not already login -> goto login.php
        header("Location: login.php");
        exit;
    }
}
?>