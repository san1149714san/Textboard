<?php
  include_once("config.php");

  $comment = strip_tags(mysql_real_escape_string($_POST['comment']));

  if (isset($_POST['inThread'])) {
    echo "in thread";
    $inThread = $_POST['inThread'];

    $query = "INSERT INTO comments (thread_id, comment, isThread, date) VALUES 
    ('$inThread', '$comment', 0, NOW());";

    $result = mysql_query($query) or trigger_error("Error getting messages!");

  } else {
    $subject = strip_tags(mysql_real_escape_string($_POST['subject']));

    $query = "INSERT INTO comments (subject, comment, isThread, date) VALUES 
    ('$subject','$comment', 1, NOW());";

    $result = mysql_query($query) or trigger_error("Error getting messages!");
  }


  echo "Post successful!";
?>