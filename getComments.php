<?php
    require_once('connect.php');
    require_once('commentParsing.php');
    require_once('Parsedown.php');


    class CommentHandler {
        function __construct($db) {
            $this->conn = $db;
            $this->parsedown = new Parsedown();
        }

        function addComment($name, $subject, $comment, $thread_id) {
            if ($name == "") {
                $name = "Anonymous";
            }

            if ($thread_id == -1) {
                $date = new DateTime();
                $result = $date->format('Y-m-d H:i:s');

                $data = array($subject, $comment, 1, $result, $name);
                $statement = $this->conn->prepare("INSERT INTO comments (subject, comment, isThread, date, name) VALUES (?, ?, ?, ?, ?);");
                // $statement->bind_param("ssiss", $subject, $comment, 1, NOW(), $name);
                session_destroy();
                $statement->execute($data);               
            } else {
                $date = new DateTime();
                $result = $date->format('Y-m-d H:i:s');

                $data = array($thread_id, $comment, 0, $result, $name);
                $statement = $this->conn->prepare("INSERT INTO comments (thread_id, comment, isThread, date, name) VALUES (?, ?, ?, ?, ?);");
                // $statement->bind_param("isiss", $thread_id, $comment, 0, NOW(), $name);
                session_destroy();
                $statement->execute($data);     
            }
        }

        function getComments($id) {
            $statement = $this->conn->prepare("SELECT * FROM comments WHERE id=:id AND isThread=1");
            $statement->bindParam(":id", $id, PDO::PARAM_STR);
            $statement->execute();

            //$thread = $statement->fetchAll();


            //$r = mysql_query("SELECT * FROM comments WHERE id='$id' AND isThread=1") or print("Error");
            //There are no to threads with this ID.
            if ($statement->rowCount() == 0) {
                return false;
            }

            $statement = $this->conn->prepare("SELECT subject, id, thread_id, comment, name, date FROM comments WHERE thread_id=:id OR id=:id;");
            $statement->bindParam(":id", $id, PDO::PARAM_STR);
            $statement->execute();

            $comments = $statement->fetchAll();

            $html = "
                <div class='list-group'>";

            echo $html;
            foreach ($comments as $messages) {
                $subject = $messages['subject'];
                $messageId = $messages['id'];

                $comment = $messages['comment'];
                $Parsedown = new Parsedown();
                $comment = $this->parsedown->text($comment);

                $thread_id = $messages['thread_id'];
                $name = $messages['name'];
                $date = $messages['date'];

                //Explode the comment in to an array seperated by the breaklines.
                $pieces = explode("<br />", $comment);

                //Implode the array that has now been wrapped.
                $comment = implode(" <br /> ", wrapComment($pieces));

                //echo "<section class='threadComment'>";
                if ($messageId == $id) {
                    $html = "
                    <a onclick='reply($messageId)' class='list-group-item list-group-item-action thread'>
                        <h5 class='list-group-item-heading '><span class='subject'>$subject</span>| <span class='name'>$name</span> | $date | No.$messageId </h5>";

                    echo $html;
                } else {
                    $html = "
                    <a onclick='reply($messageId)' class='list-group-item list-group-item-action'>
                        <h5 class='list-group-item-heading'><span class='name'>$name</span> | $date | No.$messageId</h5>";
                    echo $html;
                }

                $html = "
                        <p class='list-group-item-text'>$comment</p>
                    </a>
                ";
                echo $html;                
            }

            echo "</div>";
            return true;
        }        
    }



?>