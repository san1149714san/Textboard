<?php

  session_start();

  //Destroy it.
  session_destroy();

  //Redirect the user to the index page.
  header("location: index.php");

?>