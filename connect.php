<?php
    DEFINE ("DBUSER", "mysqladm");
    DEFINE ("DBPW", "password");
    DEFINE ("DBHOST", "localhost");
    DEFINE ("DBNAME", "messages");

    if ($dbc = mysql_connect(DBHOST, DBUSER, DBPW)) {
        if (!mysql_select_db(DBNAME)) {
            trigger_error("Database could not be selected " . mysql_error);
            exit();
        }
    }
?>