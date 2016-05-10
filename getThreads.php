<?php
    session_start();

    require_once('connect.php');

    function wrapGreentext($text) {
        return "<span class='greentext'>$text</span>";
    }

    function getThreads() {
        $query = "SELECT subject, date, id, thread_id, comment, isThread FROM comments ORDER BY date DESC;";

        $result = mysql_query($query) or trigger_error("Error getting threads.");

        if (isset($_SESSION['username'])) {
            if ($_SESSION['username'] == "admin") {
                echo "ADMIN";
            }
        }

        $visited = array();
        //As 
        while ($messages = mysql_fetch_array($result, MYSQLI_ASSOC)) {
            if ($messages['isThread'] != 1) {
                if (!in_array($messages['thread_id'], $visited)) {
                    printThreads($messages['thread_id']);
                    array_push($visited, $messages['thread_id']);
                }
            } else {
                if (!in_array($messages['id'], $visited)) {
                    printThreads($messages['id']);
                    array_push($visited, $messages['id']);
                }
            }
        }
    }

    //Takes a thread ID, and prints the thread.
    function printThreads($id) {
        $query = "SELECT subject, date, id, thread_id, comment, isThread FROM comments WHERE id='$id'";

        $result = mysql_query($query) or trigger_error("Error getting threads.");

        while ($messages =  mysql_fetch_array($result, MYSQLI_ASSOC)) {
            $thread = $messages['thread_id'];
            $subject = $messages['subject'];
            $id = $messages['id'];
            $comment = $messages['comment'];
            $date = $messages['date'];

            $getReplies = mysql_query("SELECT COUNT(thread_id) AS total FROM comments WHERE thread_id=$id;");
            $replyCount = mysql_result($getReplies, 0);
            echo "<hr>";
            echo "<div id='thread'>";
            echo "<div class='comment' id='mainPost'>";
            echo "<span id='subject'>$subject</span><span class='name'>Anonymous</span><span class='date'>$date</span>";
            echo "<a name='$id' href='index.php?id=$id'><span id='id'>No.$id</span></a>";
            if (isset($_SESSION['username'])) {
                if ($_SESSION['username'] == "admin") {
                    echo "<form action='delete.php' method='post'>";
                    echo "<input type='hidden' name='toDelete' value='$id'>";
                    echo "<input type='submit' value='delete'>";
                    echo "</form>";
                }
            }
            echo "<a id='link' href='index.php?id=$id'>Reply ($replyCount)</a>";

            echo "<span class='commentText'><p class='test'>$comment</p></span>";
            echo "</div>";

            $threadComments = "SELECT * FROM (SELECT * FROM (SELECT * FROM comments ORDER BY date DESC LIMIT 3) sub) test ORDER BY date ASC LIMIT 3";
            // $threadComments = "SELECT date, id, comment FROM comments WHERE 
            //   thread_id='$id' ORDER BY date DESC LIMIT 3";

            $comments = mysql_query($threadComments) or trigger_error("Error getting replies");

            while ($replies = mysql_fetch_array($comments, MYSQLI_ASSOC)) {
                $id = $replies['id'];
                $comment = $replies['comment'];
                $date = $replies['date'];

                echo "<div class='threadreply'>";
                echo "<div class='comment'>";
                echo "<span class='name'>Anonymous</span><span class='date'>$date</span>";
                echo "<a name='$id' href='index.php?id=$id'><span id='id'>No.$id</span></a>";
                if (isset($_SESSION['username'])) {
                    if ($_SESSION['username'] == "admin") {
                        echo "<form action='delete.php' method='post'>";
                        echo "<input type='hidden' name='toDelete' value='$id'>";
                        echo "<input type='submit' value='delete'>";
                        echo "</form>";
                    }
                }
                echo "<span class='commentText'><p>$comment</p></span>";
                echo "</div>";
                echo "</div>";
            }

            echo "</div>";
        }

    }
?>