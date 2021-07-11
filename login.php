<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location:welcomepage.php");
    exit;
}



require_once "config.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST['username']))) {
        $username_err = "Empty field username";
    } else {
        $username = trim($_POST["username"]);
    }


    if (empty(trim($_POST['password']))) {
        $password_err = "empty password field";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id,username,password FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {

                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            header(("location:welcomepage.php"));
                        } else {
                            $login_err = "invalid username password";
                        }
                    }
                } else {
                    $login_err = "invalid password or username";
                }
            } else {
                echo "oops something went wrong ";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <?php


    if (!empty($login_err)) {
        echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>
    <h1>---LOGIN FORM---</h1>
    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid  ' : '';  ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="regesterationform.php">Signup</a>
    </form>


</body>





</html>