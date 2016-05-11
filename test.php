<?php
$re = '/# Split sentences on whitespace between them.
    (?<=                # Begin positive lookbehind.
      [.!?]             # Either an end of sentence punct,
    | [.!?][\'"]        # or end of sentence punct and quote.
    )                   # End positive lookbehind.
    (?<!                # Begin negative lookbehind.
      Mr\.              # Skip either "Mr."
    | Mrs\.             # or "Mrs.",
    | Ms\.              # or "Ms.",
    | Jr\.              # or "Jr.",
    | Dr\.              # or "Dr.",
    | Prof\.            # or "Prof.",
    | Sr\.              # or "Sr.",
    | T\.V\.A\.         # or "T.V.A.",
                        # or... (you get the idea).
    )                   # End negative lookbehind.
    \s+                 # Split on whitespace between sentences.
    /ix';




    function wrapGreentext($text) {
        $test = $text;

        foreach($test as $key=>$value) {
            //echo substr($value, 0, 1);
            if (substr($value, 0, 1) == ">") {
                $test[$key] = wrapIt($value);
            }
        }

        return $test;
    }

    function wrapIt($str) {
        return "<span class='greentext' style='color: #789922'>$str</span>";
    }
    $stri = "Hello, how are you? >I am good. You sure?";

    $sentences = preg_split($re, $stri, -1, PREG_SPLIT_NO_EMPTY);


    echo implode(" ", wrapGreentext($sentences));


?>