<?php
    //we have to start the session again.
    session_start();
    //once started the session then just destroy the session,
    //that means user cannot abel to access the session again.
    session_destroy();
    //once destroy the session redirect to the "login.php" page again.
    //the header() function is used to redirect to new web page "login.php" page.
    //argument should be ("Location : login.php").
    header("Location: login.php");
?>