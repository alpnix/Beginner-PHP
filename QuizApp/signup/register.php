<?php 

include("../config/connect_db.php");

$emailError = FALSE;
$userNameError = FALSE;
$passwordError = FALSE;
$error_label = FALSE;

if (isset($_POST["submit"])) {
    // some validation
    $userName = mysqli_real_escape_string($conn,$_POST["userName"]);
    $email = mysqli_real_escape_string($conn,$_POST["email"]);
    $password = mysqli_real_escape_string($conn,$_POST["password"]);

    // built in email validation
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $emailError = "Enter Valid Email";
    } 

    // if user name is not only letters and spaces
    if (!preg_match("/^[a-zA-Z ]*$/",$userName)) {
        $userNameError = "Only letters and spaces";
    }

    // if password is less than  
    if (preg_match("/^[-]*$/",$password)) {
        $passwordError = "Should not contain dashes";
    } else if(strlen($password) < 6) {
        $passwordError = "At least 6 characters";
    }
    
    $emailSQL = "SELECT email FROM users";
    $email_results = mysqli_query($conn,$emailSQL);
    $emails = $email_results->fetch_all();
 
    for ($i=0,$n=sizeof($emails);$i<$n;$i++) {
        if ($emails[$i][0] == $email) {
            $error_label = "This account already exists!";
        }
    }

    $email_results->free();

    if ($emailError || $userNameError || $passwordError || $error_label) {
        
    } else {
        // save to database
        $sql = "INSERT INTO users (user_name,email,password) VALUES ('$userName','$email','$password')";

        if (mysqli_query($conn,$sql)) {
            session_start();
            $_SESSION["userName"] = $userName;
            $_SESSION["email"] = $email;
        
            header("Location: ../index.php");

        } else {
            echo "Connection Erorr: " . mysqli_error($conn);
        }
    }

}

mysqli_close($conn)
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        :root {
        --input-padding-x: .75rem;
        --input-padding-y: .75rem;
        }

        html,
        body {
        height: 100%;
        }

        body {
        display: -ms-flexbox;
        display: -webkit-box;
        display: flex;
        -ms-flex-align: center;
        -ms-flex-pack: center;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        }

        .form-signin {
        width: 100%;
        max-width: 420px;
        padding: 15px;
        margin: 0 auto;
        }

        .form-label-group {
        position: relative;
        margin-bottom: 1rem;
        }

        .form-label-group > input,
        .form-label-group > label {
        padding: var(--input-padding-y) var(--input-padding-x);
        }

        .form-label-group > label {
        position: absolute;
        top: 0;
        left: 0;
        display: block;
        width: 100%;
        margin-bottom: 0; /* Override default `<label>` margin */
        line-height: 1.5;
        color: #495057;
        border: 1px solid transparent;
        border-radius: .25rem;
        transition: all .1s ease-in-out;
        }

        .form-label-group input::-webkit-input-placeholder {
        color: transparent;
        }

        .form-label-group input:-ms-input-placeholder {
        color: transparent;
        }

        .form-label-group input::-ms-input-placeholder {
        color: transparent;
        }

        .form-label-group input::-moz-placeholder {
        color: transparent;
        }

        .form-label-group input::placeholder {
        color: transparent;
        }

        .form-label-group input:not(:placeholder-shown) {
        padding-top: calc(var(--input-padding-y) + var(--input-padding-y) * (2 / 3));
        padding-bottom: calc(var(--input-padding-y) / 3);
        }

        .form-label-group input:not(:placeholder-shown) ~ label {
        padding-top: calc(var(--input-padding-y) / 7);
        padding-bottom: calc(var(--input-padding-y) / 3);
        font-size: 12px;
        color: #777;
        }
        #inputEmail, #inputPassword, #userName {
            height: 50px;
            font-size: 18px;
        }
        .form-label-group {
            clear: both;
        }
    </style>
</head>
    
    <body>
    <form class="form-signin" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
      <div class="text-center mb-4">
        <img class="mb-4 img-lg" src="https://images.freeimg.net/rsynced_images/quiz-2058888_1280.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Welcome to QuizApp</h1>
        <p>This is a quiz app where you can solve various types of quizzes and have fun while learning.</p>
        <h3 class="alert-danger">
          <?php if($error_label) {echo $error_label;} ?>
        </h3>
      </div>

      <div class="form-label-group md-form">
        <input maxlength="30" name="userName" id="userName" type="text" class="form-control" 
         placeholder="User Name" value="<?php if(isset($_POST["submit"])) {echo $userName;} ?>" required autofocus>
        <label style="color:<?php if($userNameError) {echo "red";} ?>" for="userName">
            <?php if($userNameError) {echo $userNameError;} else {echo "User Name";} ?>
        </label>
      </div>

      <div class="form-label-group">
        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="\n"
         value="<?php if(isset($_POST["submit"])) {echo $email;} ?>" required>
        <label style="color:<?php if($emailError) {echo "red";} ?>" for="inputEmail">
            <?php if($emailError) {echo $emailError;} else {echo "Email address";} ?>
        </label>
      </div>

      <div class="form-label-group">
        <input maxlength="50" name="password" type="password" id="inputPassword" placeholder="password"
         value="<?php if(isset($_POST["submit"])) {echo $password;} ?>" class="form-control" required>
        <label style="color:<?php if($passwordError) {echo "red";} ?>" for="inputPassword">
            <?php if($passwordError) {echo $passwordError;} else {echo "Password";} ?>
        </label>
      </div>

      <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Sign in</button>

      <!--<?php include("../templates/footer.php"); ?>-->

    </form

    </body>
</html>