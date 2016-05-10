<?php
  require('connect.php');  
  require('getThreads.php');
  require('getComments.php');
?>

<!DOCTYPE HTML5>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
  </head>
  <body>
    <h1><a href="index.php">/prog/ - Programming</a></h1>
    <div id='submitForm'>
      <form action="postComment.php" method="post">
      <?php
        if (isset($_GET['id'])) {
          $get = $_GET['id'];
          echo "<input type=hidden name='inThread' value='$get' />";
          echo "Comment: <textarea id='commentBox' type='text' name='comment' rows=40></textarea><br>";
        } else {
          echo "Subject: <input type='text' name='subject'><br>";
          echo "Comment: <textarea id='commentBox' type='text' name='comment' rows=40></textarea><br>";
        }
      ?>
      <input type="submit">
      </form>
    </div>
    <script>
      function reply(replyID) {
        document.getElementById("commentBox").value += (">>" + replyID);
      }

    </script>

    <?php
      if (isset($_GET['id'])) {
        if (getComments(intval($_GET['id'])) == false) {
          header('Location: 404.php');
        }
      } else {
        getThreads();
      }
    ?>
  </body>
</html>

