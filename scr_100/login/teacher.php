<?php
  // Initialize the session
  session_start();

  // Check if the user is already logged in, if yes then redirect him to welcome page
  if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../../");
    exit;
  }

  // Include config file
  require_once "../../config.php";

  // Define variables and initialize with empty values
  $email_teacher = $password = $name_teacher = $address_teacher = $phone_number = "";
  $email_teacher_err = $password_err = "";

  // Processing form data when form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["email_teacher"]))) {
      $email_teacher_err = "Please enter your email!";
    } else {
      $email_teacher = trim($_POST["email_teacher"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
      $password_err = "Please enter your password.";
    } else {
      $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_teacher_err) && empty($password_err)) {
      // Prepare a select statement
      $sql = "SELECT id, contact, email, password, name, address FROM teacher_profile WHERE email = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email_teacher);

        // Set parameters
        $param_email_teacher = $email_teacher;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
          // Store result
          mysqli_stmt_store_result($stmt);
          // Check if username exists, if yes then verify password
          if (mysqli_stmt_num_rows($stmt) == 1) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $id, $phone_number, $email_teacher, $hashed_password, $name_teacher, $address_teacher);
            if (mysqli_stmt_fetch($stmt)) {
              if (password_verify($password, $hashed_password)) {
                // Password is correct, so start a new session
                session_start();

                // Store data in session variables
                $_SESSION["loggedin"] = true;
                // $_SESSION["id"] = $id;
                $_SESSION["address_teacher"] = $address_teacher;
                $_SESSION["name_teacher"] = $name_teacher;
                $_SESSION["phone_teacher"] = $phone_number;
                $_SESSION["email_teacher"] = $email_teacher;
                $_SESSION["role"] = "teacher";
               // $_SESSION["first_name"] = $first_name;

                // Redirect user to scr_1003 page
                header("location: ../../scr_100x/scr_1003/scr_1003.php");
              } else {
                // Display an error message if password is not valid
                $password_err = "The password you entered was not valid.";
              }
            }
          } else {
            // Display an error message if username doesn't exist
            $email_teacher_err = "No account found with that your email!";
          }
        } else {
          echo "Oops! Something went wrong. Please try again later.";
        }
        // Close statement
        mysqli_stmt_close($stmt);
      }
    }

    // Close connection
    mysqli_close($link);
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <title>Login</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;
    }

    button:hover {
      opacity: 0.8;
    }

    /* Extra styles for the cancel button */
    .custom-btn {
      width: auto;
      padding: 10px 18px;
      background-color: #f44336;
    }

    span.password {
      float: right;
      padding-top: 16px;
    }

    /* The Modal (background) */
    .login-btn {
      font-family: Poppins-Medium;
      border-radius: 25px;
      font-size: 16px;  
      text-transform: uppercase;  
      justify-content: center;     
      padding: 0 20px;
      height: 50px;
    }
    


    /* Change styles for span and cancel button on extra small screens */
    @media screen and (max-width: 300px) {
      span.password {
        display: block;
        float: none;
      }

      .custom-btn {
        width: 100%;
      }
    }
    body{
      margin:0;
      color:#6a6f8c;
      background:#c8c8c8;
      font:600 16px/18px 'Open Sans',sans-serif;
    }


    .login-wrap{
      width:100%;
      margin: auto;
      max-width:525px;
      min-height:550px;
      position:relative;
      background:url(https://raw.githubusercontent.com/khadkamhn/day-01-login-form/master/img/bg.jpg) no-repeat center;
      box-shadow:0 12px 15px 0 rgba(0,0,0,.24),0 17px 50px 0 rgba(0,0,0,.19);
    }
    .login-html{
      width:100%;
      height:100%;
      position:absolute;
      padding:90px 70px 50px 70px;
      background:rgba(50,50,99,.6);
    }
    .login-form .group{
      margin-bottom:40px;
    }
    .login-form .group .label,
    .login-form .group .input,
    .login-form .group .button{
      width:100%;
      color:#fff;
      display:block;
    }
    .login-form .group .input{
      border:none;
      padding:15px 20px;
      border-radius:25px;
      background:rgba(255,255,255,.1);
    }
  </style>
</head>

<body>
  <div class="login-wrap" style="margin-top: 75px;" >
    <form class="login-html " action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <h2 for="" class="text-white mb-4" style="font-family: Poppins-Medium;">LOGIN TEACHER</h2>
      <div class="login-form">
        <div class="group <?php echo (!empty($username_teacher_err)) ? 'has-error' : ''; ?>">
          <label for="username" class="w3-left text-white" style="font-family: Poppins-Medium;">Username Teacher</label>
          <input class="input w3-padding-large " type="text" placeholder="Enter username teacher" name="username" value="<?php echo $username_teacher; ?>" required>
        <div class="form-group <?php echo (!empty($email_teacher_err)) ? 'has-error' : ''; ?>">
          <label for="email_teacher"><b>Email Teacher</b></label>
          <input class="w3-input w3-padding-large" type="text" placeholder="Enter your email" name="email_teacher" value="<?php echo $email_teacher; ?>" required>
          <span class="w3-text-red"><?php echo $email_teacher_err; ?></span>
        </div>
        <div class="group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
          <label for="password" class="w3-left text-white" style="font-family: Poppins-Medium;">Password</label>
          <input class="input w3-padding-large" type="password" placeholder="Enter Password" name="password" required>
          <span class="w3-text-red"><?php echo $password_err; ?></span>
        </div>
        <button type="submit" class="w3-button w3-blue w3-block login-btn mb-2">Login</button>
        <p class="text-white" style="font-family: Poppins-Medium;">Don't have an account? <a class="w3-text-green" href="../register/teacher.php">Sign up now</a>.</p>
        <button type="reset" class="w3-btn w3-red btn">Reset</button>
        <button type="button" onclick="window.location.href='../../'" class="w3-btn w3-blue-gray btn">Cancel</button>
      </div>	
    </form>
  </div>
  <script>
    // Get the modal
    var modal = document.getElementById('dialog');
    // When the user clicks anywhere outside of the modal, close it
    // window.onclick = function(event) {
    //   if (event.target == modal) {
    //     modal.style.display = "none";
    //   }
    // }
    document.getElementById('dialog').style.display='block';
  </script>
</body>

</html>