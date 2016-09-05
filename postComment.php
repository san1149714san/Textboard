<?php
    include_once("connect.php");
    include_once("getComments.php");

    session_start();

    if ($_POST) {
        $temp = new CommentHandler($conn);
        
        $temp->addComment($_POST['name'], $_POST['subject'], $_POST['comment'], $_SESSION['thread_id']);
        header("Location: postsuccess.php");
    }


    //$comment = strip_tags(mysql_real_escape_string($_POST['comment']));
    // $comment = nl2br(htmlentities($_POST['comment'], ENT_QUOTES, 'UTF-8')); 
    // session_start();

    // if (isset($_POST['name'])) {
    //     $name = nl2br(htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8')); 
    // } else {
    //     $name = "Anonymous";
    // }

    // if (strlen($name) >= 20) {
    //     echo "Invalid name.";
    //     header("refresh:2; url=index.php");
    //     die();
    // }

    // if (strlen($comment) <= 0) {
    //     echo "Post too small!";
    //     header("refresh:2; url=index.php");
    //     die();
    // } else if (strlen($comment) >= 1000) {
    //     echo "Post too large!";
    //     header("refresh:2; url=index.php");
    //     die();
    // }


    // if (isset($_SESSION['thread_id'])) {
    //     $name = mysql_real_escape_string($_POST['name']);
    //     if ($_SESSION['thread_id'] == -1) {
    //         $subject = mysql_real_escape_string(strip_tags(mysql_real_escape_string($_POST['subject'])));

    //         if (strlen($subject) <= 0) {
    //             echo "Need a subject.";
    //             header("refresh:2; url=index.php");
    //             die();
    //         }

    //         $query = "INSERT INTO comments (subject, comment, isThread, date, name) VALUES 
    //         ('$subject','$comment', 1, NOW(), '$name');";

    //         session_destroy();

    //         $result = mysql_query($query) or trigger_error("Error inserting comment.");     
    //     } else {
    //         $inThread = $_SESSION['thread_id'];

    //         $query = "INSERT INTO comments (thread_id, comment, isThread, date, name) VALUES 
    //         ('$inThread', '$comment', 0, NOW(), '$name');";

    //         $result = mysql_query($query) or trigger_error("Error getting messages!");    
    //         session_destroy();
    //     }
    // } 

    // header("Location: postsuccess.php");
?>