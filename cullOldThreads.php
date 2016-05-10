<?php
    require_once('connect.php');

    DEFINE("MAXTHREADS", 10);

    function cull() {

        // $threadQuery = mysql_query("SELECT COUNT(isThread) AS total FROM comments WHERE isThread='$one';");
        // $threadCount = mysql_result($threadQuery, 0);


        $threadComments = "SELECT date, id, comment FROM comments WHERE 
          isThread=1 ORDER BY date ASC ";

        $result = mysql_query($threadComments) or trigger_error("Error threads.");
        $threadCount = mysql_num_rows($result);
        echo $threadCount;

        while ($row = mysql_fetch_array($result) and $threadCount >= MAXTHREADS) {
            $id = $row['id'];
            $test = mysql_query("DELETE FROM comments WHERE id=$id") or print(mysql_error());
            $threadCount--;
        }

        mysql_free_result($result);
    }
?>