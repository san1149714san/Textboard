<?php
    require_once('connect.php');

    function getComments($id) {
      $r = mysql_query("SELECT isThread, id FROM comments WHERE id='$id' AND isThread=1") or print("Error");
      if (mysql_num_rows($r) == 0) {
        return false;
      }

      $query = "SELECT subject, id, thread_id, comment, isThread FROM comments WHERE thread_id='$id' OR id='$id';";
      $result = mysql_query($query) or trigger_error("Error getting comments!");


      while ($messages = mysql_fetch_array($result, MYSQLI_ASSOC)) {
          $subject = $messages['subject'];
          $messageId = $messages['id'];
          $comment = $messages['comment'];
          $thread_id = $messages['thread_id'];

          echo "<div class='threadComment'>";
          if ($messageId == $id) {
            echo "<span id='subject'>$subject</span><span class='name'>Anonymous</span>";
          } else {
            echo "<span class='name'>Anonymous</span>";
          }
          echo "<a name='$messageId' href='#$messageId' onclick='reply($messageId)'><span id='id'>No.$messageId</span></a>";
          echo "<br>$comment<br>";
          echo "</div>";
      }

      return true;
    }
?>