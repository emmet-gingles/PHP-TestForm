<?php
// php file used for database connection
require_once 'db_login.php';

// retrieving data from AJAX post request
if(isset($_POST['data'])) {
    // decode the JSON data and set both email and password to a variable
    $json_data = json_decode($_POST['data'], false);
    $email = $json_data->email;
    $pword = $json_data->pword;

    // try to connect using connection parameters
    $db_server = mysqli_connect($db_hostname, $db_username, $db_password);
    // terminate if connection unsuccessful
    if (!$db_server) {
        die("Unable to connect to MySQL: " . mysqli_connect_error());
    }

    // select database
    if(mysqli_select_db($db_server, $db_name)){
        // use email to find the row of data for the user
        $select = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($db_server, $select);
        $num_rows = mysqli_num_rows($result);
        // if user found in database
        if($num_rows > 0){
            $row = mysqli_fetch_row($result);
            $hash = $row[4];

            // because the password stored is hashed we use this function to verify it is the same as the password entered
            // if it is - create cookies for both the userId and the user's firstname
            if(password_verify($pword, $hash)){
                setcookie("userId", $row[0]);
                setcookie("user", $row[1]);
                echo "Success";
            }
            // else passwords do not match
            else{
                echo "Fail";
            }
        }
        // else user not found in database
        else{
            echo "Fail";
        }

    }
}