<?php
  
  require("connect.php");

  if (isset($_POST['toDelete'])) {
    $id = $_POST['toDelete'];
    echo $id;
  }

  $query = "DELETE FROM comments WHERE id='$id' OR thread_id='$id'";

  $result = mysql_query($query) or trigger_error("Error deleting comment.");


  //echo $_POST['toDelete'];

?>