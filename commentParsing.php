<?php
    function wrapGreentext($text) {
        $test = $text;

        foreach($test as $key=>$value) {
            //echo substr($value, 0, 7);
            if (substr($value, 0, 7) === "&gt;&gt") {
                $test[$key] = replyLink($value);
            } else if (substr($value, 0, 3) === "&gt" && substr($value, 0, 7) != "&gt;&gt") {
                $test[$key] = greenText($value);
            }
        }

        return $test;
    }

    function replyLink($text) {
        //return $text;
        $id = substr($text, 8);

        $sql = "SELECT id, thread_id FROM comments WHERE id='$id';";

        $result = mysql_query($sql);

        if (mysql_num_rows($result) > 0) {

            while ($messages = mysql_fetch_array($result, MYSQLI_ASSOC)) {
                $messageId = $messages['id'];
                $thread_id = $messages['thread_id'];

                return "<a href='index.php?id=$thread_id#$messageId'>$text</a>";
            }
  
        } else {
            return $text;
        }






    }

    function greenText($text) {
        return "<span class='greentext'>$text</span>";
    }
?>