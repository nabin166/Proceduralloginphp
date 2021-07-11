<?php
require_once "config.php";

//define empty value for name password
$username = $password = $conform_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = " Please enter username";
    } elseif (!preg_match('/^[a-zA-z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "username can only contain letters ,numbers and underscores";
    } else {

        $sql = "SELECT id from users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim(($_POST["username"]));
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);


                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "username already taken";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                $username = trim($_POST["username"]);
            }
        } else {
            echo "opps, Something went wrong";
        }
        mysqli_stmt_close($stmt);
    }


    // validate password

    if (empty(trim($_POST["password"]))) {
        $password_err = "please enter a password";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "password must be 6 character";
    } else {
        $password = trim($_POST["password"]);
    }

    //validate conform password
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "please confirm password";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "password does't match";
        }
    }

   

    if (empty($username_err) && empty($password_err) && empty($conform_password_err)) {

        $sql = "INSERT INTO users(username, password) VALUES(?,?); ";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            } else {
                echo "oooopsss , something went wrong";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    <h1>---SIGNUP FORM---</h1>


 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

   

    <form action="" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid  ' : '';  ?>"
                  value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="conform_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $conform_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div> 
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>




</body>

</html>

