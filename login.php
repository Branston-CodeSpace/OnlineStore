<?php 

session_start();

require_once "connect.php";

$username = $password = "";
$username_error = $password_error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(empty(trim($_POST["username"]))){
    $username_error = "Please enter username.";
  }else{
    $username = trim($_POST["username"]);
  }

  if(empty(trim($_POST["password"]))){
    $password_error = "Please enter your password.";
  }else{
    $password = trim($_POST["password"]);
  }

  if(empty($username_error) && empty($password_error)){
    $sql = "SELECT user_id, username, password, confirm FROM users WHERE username = ?";

    if($stmt = $mysqli->prepare($sql)){
      $stmt->bind_param("s", $param_username);
      $param_username = $username;

      if($stmt->execute()){
        $stmt->store_result();

        if($stmt->num_rows == 1){
          $stmt->bind_result($id, $username, $hashed_password, $confirm);

          if($stmt->fetch()){
            if(password_verify($password, $hashed_password) && $confirm == 1){

              session_start();

              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;

              header("location: index.php");
            }else{
              $password_error = "The password you entered is not valid.";
            }
          }
        }else{
          $username_error = "No account found with that username.";
        }
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
    <div>
    <h3>Log in</h3>

        <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> method="post">
          <div class=" <?php echo (!empty($username_error)) ? 'has-error' : ''; ?>">  
            <label>Username:</label><br>
            <input type="text" name="username"><br>
            <span class="help-block"><?php echo $username_error; ?></span>
          </div>
          <div class="<?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
            <label>Password</label><br>
            <input type="password" name="password"><br>
            <span><?php echo $password_error; ?></span>
          </div>
          <div>
            <input type="submit" value="Login">
          </div>
          <p>Don't have an account? <a href="signup.php">Sign up.</p>
        </form>

    </div>
        
  </body>