<?php
    session_start();
  
    require("connect.php");

    if (isset($_POST['toDelete']) && $_SESSION['username'] == 'admin') {
        $id = $_POST['toDelete'];
        deletePost($id);
    }

    function deletePost($id) {
      $query = "DELETE FROM comments WHERE id='$id' OR thread_id='$id'";

      $result = mysql_query($query) or trigger_error("Error deleting comment.");
      echo "POST DELETED.";
      header("refresh:2; url=index.php");
    }
?>