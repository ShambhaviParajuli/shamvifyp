<?php 
    session_start();

    require "./pdo.php";
    require "./utils.php";
    /* echo $_SESSION['fid'];
    debug($_POST); */

    /* if(!isset($_POST)){
        header("location: register.php");
        exit;
    } */

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if(isset($_POST, $_POST['fname'], $_POST['lname'], $_POST['age'], $_POST['address'], $_POST['email'])){
            $fname = validate($_POST['fname']);
            $lname = validate($_POST['lname']);
            $age = validate($_POST['age']);
            $addr = validate($_POST['address']);
            $email = validate($_POST['email']);
            $pass = validate($_POST['password']);
            try{
                $stmt=$conn->prepare("UPDATE into patient_details SET fname=:fname, lname=:lname, age=:age, address=:addr, email=:email) WHERE fname=NULL");
                $stmt->execute([
                    "fname"=>$fname, 
                    "lname"=>$lname, 
                    "age"=>$age,
                    "addr"=>$addr,
                    "email"=>$email,
                ]);

                
                $_SESSION['success'] = "Your data has been registered successfully!";
                header("location: login.php");
                exit;
            }catch(Exception $e){
                $_SESSION['error'] = $e;
                header("location: register.php");
                exit;
            }        
        }
        else{
            $_SESSION['error'] = "Please fill in the details!";
            header('location: register.php');
            exit;
        }
        
    }
    
   
    else{
        $_SESSION['error'] = "Please fill in the details!";
        header('location: register.php');
        exit;
    }


?>