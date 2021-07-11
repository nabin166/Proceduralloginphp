<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="text-align :center">


    <h1>WELCOME</h1>
    <div style="margin:1%">
        <?php

        include "config.php";
        $sql = "SELECT * FROM users WHERE username = ?";
        if ($stmt =  mysqli_prepare($conn, $sql)) {


            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if ($count = mysqli_stmt_num_rows($stmt)) {
                    $ram = mysqli_stmt_fetch($stmt);
                    $sama =  mysqli_stmt_get_result($stmt);
                    
                 
                  echo $sama;
                }
            }
        }

        ?>
    </div>

    <div><a href="logout.php" style="background-color: green; color:wheat; padding:5px ; border-radius: 3px;">Logout</a></div>
</body>

</html>