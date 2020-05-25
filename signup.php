<?php

require_once 'connect.php';

session_start();

$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

  if(empty(trim($_POST["username"]))){
    $username_err = "Please enter a username.";
  }else{
    $sql = "SELECT id FROM users WHERE username = ?";

    if($stmt = $mysqli->prepare($sql)){
      $stmt->bind_param("s", $param_username);
      $param_username = trim($_POST["username"]);

      if($stmt->execute()){
        $stmt->store_result();

        if($stmt->num_rows == 1){
          $username_err = "This username is already taken.";
        }else{
          $username = trim($_POST["username"]);
          $_SESSION["username"] = $username;
        }
      }else{
        echo "Something went wrong. Please try again later.";
      }
    }
    $stmt->close();
  }

  if(empty(trim($_POST["password"]))){
    $password_err = "Please enter a password.";
  }elseif(strlen(trim($_POST["password"])) < 6){
    $password_err = "Password must have atleast 6 characters.";
  }else{
    $password = trim($_POST["password"]);
  }

  if(empty(trim($_POST["confirm_password"]))){
    $confirm_password_err = "Please confirm password.";
  }else{
    $confirm_password = trim($_POST["confirm_password"]);
    
    if(empty($password_err) && ($password != $confirm_password)){
      $confirm_password_err = "Password did not match.";
    }
  }

  if(empty($username_err) && empty($empty_err) && empty($password_err) && empty($confirm_password_err)){
    
    $sql = "INSERT INTO users (username, password, email, confirmNum) VALUES (?, ?, ?, ?)";

    if($stmt = $mysqli->prepare($sql)){
      $stmt->bind_param("ssss", $param_username, $param_password, $param_email, $param_confirmNum);

      $param_username = $username;
      $param_email = $email;
      $param_confirmNum = rand(10000, 99999);
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      if($stmt->execute()){
        $_SESSION["confirmNum"] = $param_confirmNum;
        header("location:");
      }else{
        echo "Something went wrong. Please try again later.";
      }
    }
    $stmt->close();
  }
  $mysqli->close();
}

?>

  <body>
      
    
 
  </body>
