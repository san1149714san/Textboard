<?php
    session_start();

    require_once('connect.php');
    require_once('commentParsing.php');


    class ThreadHandler {
        function __construct($db) {
            $this->conn = $db;
        }


        function getThreads() {
            $statement = $this->conn->prepare("SELECT subject, date, id, thread_id, comment, isThread FROM comments ORDER BY date DESC;");
            $statement->execute();

            $thread = $statement->fetchAll();
            $visited = array();


            foreach ($thread as $row) {
                if ($row['isThread'] != 1) {
                    if (!in_array($row['thread_id'], $visited)) {
                        $this->printThreads($row['thread_id']);
                        array_push($visited, $row['thread_id']);
                    }
                } else {
                    if (!in_array($row['id'], $visited)) {
                        $this->printThreads($row['id']);
                        array_push($visited, $row['id']);
                    }
                }
            }
        }

        //Takes a thread ID, and prints the thread.
        function printThreads($id) {
            // $query = "SELECT subject, date, id, thread_id, comment, isThread, name FROM comments WHERE id='$id'";

            $statement = $this->conn->prepare("SELECT subject, date, id, thread_id, comment, isThread, name FROM comments WHERE id=:id");
            $statement->bindParam(":id", $id, PDO::PARAM_STR);
            $statement->execute();

            $thread = $statement->fetchAll();

            foreach ($thread as $messages) {
                $thread = $messages['thread_id'];
                $subject = $messages['subject'];
                $id = $messages['id'];
                $comment = $messages['comment'];
                $date = $messages['date'];
                $name = $messages['name'];

                $pieces = explode("<br />", $comment);

                $comment = implode(" <br /> ", wrapComment($pieces));



                $statement = $this->conn->prepare("SELECT COUNT(thread_id) AS total FROM comments WHERE thread_id=:thread_id;");
                $statement->bindParam(":thread_id", $id, PDO::PARAM_STR);
                $statement->execute();

                $replyCount = $statement->fetchColumn();
                // $replyCount = mysql_result($getReplies, 0);

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

                $statement = $this->conn->prepare("SELECT * FROM (SELECT * FROM (SELECT * FROM comments WHERE thread_id=:thread_id ORDER BY date DESC LIMIT 3) sub) test WHERE thread_id=:thread_id ORDER BY date ASC LIMIT 3");
                $statement->bindParam(":thread_id", $id, PDO::PARAM_STR);
                $statement->execute();
            
                $comments = $statement->fetchAll();

                foreach ($comments as $replies) {
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
    }


?>