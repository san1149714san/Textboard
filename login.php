<?php

    session_start();

    require("connect.php");

    if (isset($_POST['username']) and isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    } else {
        die();
    }


    //If the username and password are not empty.
    if ($username && $password) {
        //Select row of users table where the username is the same as the user supplied username.
        $query = mysql_query("SELECT * FROM users WHERE username='$username'");

        //Get the number of rows the query returs.
        $numrows = mysql_num_rows($query);

        //If there's more than 0 rows, then there is a user with this username.
        if ($numrows != 0) {
            //Select the username and password.
            while ($rows = mysql_fetch_assoc($query)) {
                $dbusername = $rows['username'];
                $dbpassword = $rows['password'];
            }

            //If the username is the same as the supplied username, and the password is the same.
            if ($username == $dbusername && password_verify($password, $dbpassword)) {
                $_SESSION['username'] = $dbusername;
                header("location: index.php");
            } else {
                header("location: 404.php");
            }

        } else {
            header("location: 404.php");
        }
    } else {
        die("Please enter a valid username and password");
    }
?>