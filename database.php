<?php
    //database_host_name by default "localhost" don't change our host name.
    $databaseHostName = "localhost";
    //database_user_name by default "root".
    $databaseUserName = "root";
    //database_password by default "empty" so don't give any password here.
    $databasePassword = "";
    //database_name is we already creater in MySQL so you give what you given your db_name in server.
    $databaseName = "register_login_database";
    //connect to the database we using mysqli_connect() function.
    //in this function have 4 arguments.
    //arg1 is db_host_name, arg2 is db_user_name, arg3 is db_assword and arg4 is db_name.
    //this function can return true or false so we declare variable.
    $conn = mysqli_connect($databaseHostName, $databaseUserName, $databasePassword, $databaseName);
    //if $conn is return false lets stop the execution of the code.
    if(!$conn) {
        //we can use die method and we give some message.
        //so if you giving wrong information it will display the error message.
        die("Somthing went wrong...!");
    }
?>