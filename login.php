<?php
    //if user is already loged in then we will redirect to the "index.php" page.
    //when ever we do somthing with session we have to session_start() function.
    session_start();
    //now chaeck is the session with array_key "user" is not set then the user is not loged in,
    if(isset($_SESSION["user"])) {
        //if "user" is not loged in then we will direct into the "login.php" page.
        //the header() function is used to redirect to new web page "login.php" page.
        //argument should be ("Location : login.php").
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous"/>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
        <title>Login Form</title>
    </head>
    <body>
        <?php
            //isset() method is used to provide post variable with array key format.
            //When the person click the register button it check all the values are entered or not.
            if(isset($_POST['login'])) {

                //declaring variable for post variable it posting user values.
                $email = $_POST['email'];
                $password = $_POST['password'];

                //now we have to check whether or not this email and password field exist in the database.
                //if there this email and password then only we allow user to login.
                //we already created file for connecting database file name is "database.php".
                //now we should import our "database.php" file using require_once() function.
                require_once("database.php");

                //if provided email is already exist we will add the new error.
                //here we will use the new SQL command for searching provide the database
                //to see if provided email already exist.
                //syntax: select * from table_name where col_name = 'var_name'.
                $sql = "SELECT * FROM register_login_table WHERE email = '$email'";

                //now we can execute the SQL command using mysqli_query() function.
                //in this function we will provide SQL variable name as argument.
                //now we can see execution into a new variable called $result.
                //it have two argument arg1 is connection variavle we used in database.php "$conn",
                //arg2 is SQL command.
                $result = mysqli_query($conn, $sql);

                //now we convert this data into a array using mysqli_fetch_array() function.
                //now we can see execution into a new variable called $user.
                //we provide $result as an arg1 argument and arg2 is constant.
                //this function return an http array so we can easily access column of the database.
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

                //if this function return false the email not exist in the database it will return false.
                //check here if return the data then the email exist in the database.
                if($user) {
                    //if email is matching then check password is matching the specific email address or not.
                    //for verifying the password we can use password_verify() function.
                    //it have two argument,
                    //arg1 is user password $password as argument,
                    //arg2 is password into the database we can access to the $user variable indexing the name of the column ['password'].
                    if(password_verify($password, $user['password'])) {

                        //when ever somebody is loged in we will start a new session,
                        //then we will add a new value to the session global array variable
                        //A session is a way to store information in variable to be used across multiple pages.
                        session_start();

                        //we using $user as the array_key and value is "yes" that means user loged in.
                        //if session is have some value that means user loged in
                        $_SESSION["user"] = "yes";

                        //if password is verifyed return true then we can allow user to login.
                        //if email and password is verifyed user to redirect to the index page for successfully login.
                        //the header() function is used to redirect to new web page (index.php) loads correctly.
                        //argument should be ("Location : index.php").
                        //we should create index.php file.
                        header("Location: index.php");
                        //in the header() function after the location there are no need any space
                        //like this ("Location : index.php") by mistake giving any space it will display the error keep it mind that.
                        
                        die();
                    }
                    //if password is doesn't match then display the new error message.
                    else {
                        //so we will use <div> element the class from bootstrap.(it is used for alert to the users)
                        echo "<div class='alert alert-danger'>Password does not match...!</div>";
                    }
                    
                }
                //if it return false then the email does exist in the database.
                else {
                    //now displaying the errors using echo opreator.
                    //so we will use <div> element the class from bootstrap.(it is used for alert to the users)
                    echo "<div class='alert alert-danger'>Email does not match...!</div>";
                }

            }

        ?>
        <div class="login-container">
            <form action="login.php" method="post">
                <div class="form-group m-3">
                    <input type="text" name="email" placeholder="E-Mail ID:" class="form-control" />
                </div>
                <div class="form-group m-3">
                    <input type="password" name="password" placeholder="Password:" class="form-control" />
                </div>
                <div class="form-but m-3">
                    <input type="submit" name="login" value="Login" class="btn btn-primary" />
                </div>
            </form>
            <div class="ml-3">
                <p>Not registered yet
                    <a href="registration.php">Register Here</a>
                </p>
            </div>
        </div>
    </body>
</html>