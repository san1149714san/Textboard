<?php




    function wrapGreentext($text) {
        $test = $text;

        foreach($test as $key=>$value) {

            if (substr($value, 0, 1) === ">") {
                $test[$key] = wrapIt($value);
            }
        }

        return $test;
    }

    function wrapIt($str) {
        return "<span class='greentext' style='color: #789922'>$str</span>";
    }
    $stri = "Hello, how are you? <br> >I am good. You sure?";

    $pieces = explode(" <br> ", $stri);

    echo implode(" ", wrapGreentext($pieces));

?>