<?php
    //if user is already registred then we will redirect to the index.php page.
    //when ever we do somthing with session we have to session_start() function.
    session_start();
    //now chaeck is the session with array_key "user" is not set then the user is not loged in,
    if(isset($_SESSION["user"])) {
        //if "user" is not loged in then we will redirect into the "login.php" page.
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
        <title>Registration Form</title>
    </head>
    <body>
        <div class="registration-container">
            <?php
                //isset() method is used to provide post variable with array key format.
                //When the person click the register button it check all the values are entered or not.
                if(isset($_POST["submit"])) {

                    //declaring variable for post variable it posting user values.
                    $full_name = $_POST["full_name"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    $conformPassword = $_POST["conform_password"];

                    /*we give some password security, because anybody seeing your database they will
                    easily see the password,so that reason we giving password security.*/
                    //we can use password_hash(arg1, arg2) function arg1 is variable_name, arg2 is (PASSWORD_DEFAULT)constant.
                    $passwordSecurity = password_hash($password,PASSWORD_DEFAULT);

                    //Before inserting the data into the database we to do some validation,
                    //For that perpose I declaring one empty array variable.
                    //If any errors apend that is specific error into this array.
                    $error = array();
                    //if any empty values provided by the users we can use the empty method in PHP "empty()" function.
                    if(empty($full_name) || empty($email) || empty($password) || empty($conformPassword)) {
                        //if any empty value provinding by the user we add new array into the $error array.
                        //We can use array_push() function.
                        //In this array_push(arg1, arg2) function have two arg1 is name of the variable and arg2 is error message what you want.
                        array_push($error,"All fields are required...!");
                    }

                    //Now we check user given email is valid or not.
                    //we can do filter_var() function.
                    //if filter_var() function return false it will add new error into the $error array.
                    //it have two arguments filter_var(arg1, arg2) arg1 is $email and arg2 is (FILTER_VALIDATE_EMAIL) constant.
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        //same as at as what we did in empty values.
                        array_push($error,"E-Mail is not valid...!");
                    }

                    //if password length is less than 8 then we add new error.
                    //for checking lehgth of the password we use strlen() function.
                    if(strlen($password) < 8) {
                        //same as at as what we did in valid email checking.
                        array_push($error,"Please give strong password, password must be at least 8 characters...!");
                    }

                    //if password and conform password is not same then we add new error.
                    if($password !== $conformPassword) {
                        //same as at as what we did in password length checking.
                        array_push($error,"Password do not match please give correct password...!");
                    }

                    //we should connect our database.
                    //we creating new file for connecting database my file name is "database.php".
                    //now we should import our "database.php" file using require_once() function.
                    require_once("database.php");

                    //if provided email is already exist we will add the new error.
                    //here we will use the new SQL command for searching provide the database
                    //to see if provided email already exist.
                    //syntax: select * from table_name where col_name = 'var_name'.
                    $sql = "SELECT * FROM register_login_table WHERE email = '$email'";

                    //now we can execute the command using mysqli_query() function.
                    //in this function we will provide SQL variable name as argument.
                    //now we can see execution into a new variable called $result.
                    //it have two argument arg1 is connection variavle we used in database.php "$conn",
                    //arg2 is SQL command.
                    $result = mysqli_query($conn, $sql);

                    //now we can check number of values on this $result using a mysqli_num_rows() function.
                    //let check no.of rows and no.of values in this $result.
                    //we have to provide $result as arguments.
                    $rowCount = mysqli_num_rows($result);

                    //if the provided email is already exist display new error message.
                    if($rowCount > 0) {
                        //same as at as what we did in password and conform_password checking.
                        array_push($error,"E-Mail already exist...!");
                    }

                    //we can check number of array limits in a array using function called "count".
                    //if the length of the array is greater than 0 then we have errors cann't insert data into the database.
                    //count(arg) argument is name of the variable name $error.
                    if(count($error) > 0) {
                        //if the length of array is greater than 0 display the errors to the users.
                        //if the array elements have multiple values so we can check and display the values using "foreach" loop.
                        foreach($error as $errors) {
                            //now displaying the errors using echo opreator.
                            //so we will use <div> element the class from bootstrap.(it is used for alert to the users)
                            echo "<div class='alert alert-danger'>$errors</div>";
                        }
                    }

                    //if there is no error then we will insert the data.
                    else {
                        //we will insert the data into the database.
                        //inserting the data we using SQL command so we declaraing variable variable_name is $sql.
                        //we will keep it inside the "" because it is the string.
                        //syntax: insert into table_name (col1, col2,...) values(col1, col2,...);
                        //don't give directly variable_name inside the values we should give for each variable_name "?";
                        $sql = "INSERT INTO register_login_table (full_name, email, password) VALUES( ?, ?, ? )";

                        //before executing this SQL command we will have to prepare a statement.
                        //before prapering a statement we have to initiate it to a function.
                        //we use mysqli_stmt_init() function is initializes a statement and returns an object suitable for using mysqli_stmt_prepare().
                        //we stored object in a variable.
                        //in this function we have to provide the connection variavle we used in database.php "$conn".
                        $stmt = mysqli_stmt_init($conn);

                        //now we use this statement of the inside the mysqli_stmt_prepare() function.
                        //we have to provide two argument in this function.
                        //arg1 is statement object "$stmt" and arg2 is SQL command "$sql".
                        //now this function return true or false if anything append it will return false.
                        //for that reason declaring variable.
                        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);

                        //if this data is true then start adding values in the SQL command.
                        if($prepareStmt) {
                            //using mysqli_stmt_bind_param() function so you can bind the values into the SQL command.
                            //we have to provide some argument.
                            //arg1 is statement object "$stmt",
                            //arg2 is type of the value(?,?,?) this values all string so each value should be "s",
                            //arg3 is provide the actuial values 1 is $full_name, 2 is $email 3 is $passwordSecurity.
                            mysqli_stmt_bind_param($stmt, "sss", $full_name, $email, $passwordSecurity);

                            //now we can execute the command using mysqli_stmt_execute() function.
                            //we have to provide statement object as the argument
                            mysqli_stmt_execute($stmt);

                            //if everything is ok then execuction is done properly.
                            //so we will use <div> element the class from bootstrap.(it is used for alert to the users)
                            echo "<div class='alert alert-success'>You are registred successfully...!</div>";
                        }

                        //if anything is append wrong we will stop the code execution here.
                        else {
                            //we can use die method and we give some message.
                            //so if you giving wrong information it will trigger and display the message.
                            die("Somthing went wrong...!");
                        }
                        //if provided email is already exist we will add the new error
                        //before the count displaying errors to the users line number 63.
                    }
                }
            ?>

            <form action="registration.php" method="post">
                <div class="form-group m-3">
                    <input type="text" name="full_name" placeholder="Full Name:" class="form-control">
                </div>
                <div class="form-group m-3">
                    <input type="email" name="email" placeholder="E-mail ID:" class="form-control">
                </div>
                <div class="form-group m-3">
                    <input type="password" name="password" placeholder="Password:" class="form-control">
                </div>
                <div class="form-group m-3">
                    <input type="text" name="conform_password" placeholder="Conform Password:" class="form-control">
                </div>
                <div class="form-group m-3 mt-2">
                    <input type="submit" name="submit" value="Register" class="btn btn-primary mt-2">
                </div>
            </form>
            <div class="ml-3">
                <p>Already Registered
                    <a href="login.php">Login Here</a>
                </p>
            </div>
        </div>
    </body>
</html>