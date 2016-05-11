<?php
    session_start();

    require_once('connect.php');
    require_once('commentParsing.php');


    function getThreads() {
        $query = "SELECT subject, date, id, thread_id, comment, isThread FROM comments ORDER BY date DESC;";

        $result = mysql_query($query) or trigger_error("Error getting threads.");


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
        $query = "SELECT subject, date, id, thread_id, comment, isThread, name FROM comments WHERE id='$id'";

        $result = mysql_query($query) or trigger_error("Error getting threads.");

        while ($messages =  mysql_fetch_array($result, MYSQLI_ASSOC)) {
            $thread = $messages['thread_id'];
            $subject = $messages['subject'];
            $id = $messages['id'];
            $comment = $messages['comment'];
            $date = $messages['date'];
            $name = $messages['name'];

            $pieces = explode("<br />", $comment);

            $comment = implode(" <br /> ", wrapComment($pieces));

            $getReplies = mysql_query("SELECT COUNT(thread_id) AS total FROM comments WHERE thread_id=$id;");
            $replyCount = mysql_result($getReplies, 0);

            $html = "
                <section id='thread'>
                    <section class='comment' id='mainPost'>
                        <span id='subject'>$subject</span>
                        <span class='name'>$name</span>
                        <span class='date'>$date</span>
                        <a name='$id' href='index.php?id=$id'><span id='id'>No.$id</span></a>
            ";

            $adminForm = "
                        <form action='delete.php' method='post'>
                            <input type='hidden' name='toDelete' value='$id'>
                            <input type='submit' value='delete'>
                        </form>
            ";
            echo $html;

            if (isset($_SESSION['username'])) {
                if ($_SESSION['username'] == "admin") {
                    echo $adminForm;
                }
            }

            $html = "
                        <a id='link' href='index.php?id=$id'>Reply ($replyCount)</a>
                        <span class='commentText'><p class='test'>$comment</p></span>
                    </section>
            ";

            echo $html;

            $threadComments = "SELECT * FROM (SELECT * FROM (SELECT * FROM comments WHERE thread_id='$id' ORDER BY date DESC LIMIT 3) sub) test WHERE thread_id='$id' ORDER BY date ASC LIMIT 3";
            // $threadComments = "SELECT date, id, comment FROM comments WHERE 
            //   thread_id='$id' ORDER BY date DESC LIMIT 3";

            $comments = mysql_query($threadComments) or trigger_error("Error getting replies");

            while ($replies = mysql_fetch_array($comments, MYSQLI_ASSOC)) {
                $id = $replies['id'];
                $comment = $replies['comment'];
                $date = $replies['date'];
                $thread_id = $replies['thread_id'];
                $name = $replies['name'];


                $pieces = explode("<br />", $comment);
                //print_r($pieces);
                $comment = implode(" <br /> ", wrapComment($pieces));

                $html = "
                    <section class='threadreply'>
                        <div class='comment'>
                            <span class='name'>$name</span><span class='date'>$date</span>
                            <a name='$id' href='index.php?id=$thread_id#$id'><span id='id'>No.$id</span></a>
                ";

                echo $html;



                $adminForm = "
                            <form action='delete.php' method='post'>
                                <input type='hidden' name='toDelete' value='$id'>
                                <input type='submit' value='delete'>
                            </form>
                ";

                if (isset($_SESSION['username'])) {
                    if ($_SESSION['username'] == "admin") {
                        echo $adminForm;
                    }
                }

                $html = "
                            <span class='commentText'><p>$comment</p></span>
                        </div>
                    </section>
                ";

                echo $html;

            }

            $html = "
                </section>
                <hr>
            ";

            echo $html;
        }

    }
?>