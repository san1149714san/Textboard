<!DOCTYPE HTML5>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <h1>ADMIN</h1>
    <?php
        require_once('connect.php');

        $user = 'admin';
        $password = password_hash("changethis", PASSWORD_DEFAULT, ['cost' => 12]);

        $r = mysql_query("SELECT * FROM users WHERE username='$user'");

        //If there isn't an admin in the users database, add one.
        if (mysql_num_rows($r) == 0) {
            $query = "INSERT INTO users (username, password) VALUES ('$user', '$password');";
            $result = mysql_query($query);  
        }
    ?>
    <form action="login.php" method="post">
      Username: <input type="text" name="username">
      Password: <input type="password" name="password">
      <input type="submit" value="submit">
    </form>
  </body>
</html>


