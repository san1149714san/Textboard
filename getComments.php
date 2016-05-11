<?php
    require_once('connect.php');
    require_once('commentParsing.php');



    function getComments($id) {
        $r = mysql_query("SELECT * FROM comments WHERE id='$id' AND isThread=1") or print("Error");
        //There are no to threads with this ID.
        if (mysql_num_rows($r) == 0) {
            return false;
        }

        $query = "SELECT subject, id, thread_id, comment, name, date FROM comments WHERE thread_id='$id' OR id='$id';";
        $result = mysql_query($query) or trigger_error("Error getting comments!");


        while ($messages = mysql_fetch_array($result, MYSQLI_ASSOC)) {
            $subject = $messages['subject'];
            $messageId = $messages['id'];
            $comment = $messages['comment'];
            $thread_id = $messages['thread_id'];
            $name = $messages['name'];
            $date = $messages['date'];

            //Explode the comment in to an array seperated by the breaklines.
            $pieces = explode("<br />", $comment);

            //Implode the array that has now been wrapped.
            $comment = implode(" <br /> ", wrapComment($pieces));

            $html = "
                <section class='threadComment'>";

            echo $html;
            //echo "<section class='threadComment'>";
            if ($messageId == $id) {
                $html = "
                    <span id='subject'>$subject</span><span class='name'>$name</span><span class='date'>$date</span>";

                echo $html;
            } else {
                $html = "
                    <span class='name'>$name</span><span class='date'>$date</span>";
                echo $html;
            }

            $html = "
                    <a name='$messageId' href='#$messageId' onclick='reply($messageId)'><span id='id'>No.$messageId</span></a>
                    <p class='test'>$comment</p>
                </section>
            ";

            echo $html;
        }

        return true;
    }
?>