<?php
// php file used for database connection
require_once 'db_login.php';

// retrieving data from AJAX post request
if(isset($_POST['data'])) {
    // decode the JSON data and set each part to a variable
    $json_data = json_decode($_POST['data'], false);
    $fname = $json_data->fname;
    $lname = $json_data->lname;
    $email = $json_data->email;
    $pword = $json_data->pword;
    $gender = $json_data->gender;
    $county = $json_data->county;
    $address = $json_data->address;

    // try to connect using connection parameters
    $db_server = mysqli_connect($db_hostname, $db_username, $db_password);
    // terminate if connection unsuccessful
    if (!$db_server) {
        die("Unable to connect to MySQL: " . mysqli_connect_error());
    }

    // creates database if it doesn't already exist
    $create_db = 'CREATE DATABASE IF NOT EXISTS' . $db_name;
    if (!mysqli_num_rows(mysqli_query($db_server, "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . $db_name . "'"))) {
        mysqli_query($db_server, $create_db);
    }
    // select database
    mysqli_select_db($db_server, $db_name);

    // structure of the table to be created
    $create_table = "CREATE TABLE IF NOT EXISTS users(
                  userID INT AUTO_INCREMENT,
                  firstname VARCHAR(50) NOT NULL, 
                  lastname VARCHAR(50) NOT NULL, 
                  email VARCHAR(100) NOT NULL,
                  password CHAR(64) NOT NULL,
                  gender VARCHAR(10) NOT NULL,
                  address VARCHAR(250) NOT NULL,
                  county VARCHAR(25) NOT NULL,
                  PRIMARY KEY (userID)
                  ) AUTO_INCREMENT=1000";

    // need to check that the email address does not already belong to another user
    if (mysqli_query($db_server, $create_table)) {
        $select = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($db_server, $select);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0){
            die("Error: Account with that email address already exists");
        }
        else{
            // hash the password using Bcrypt to make it more secure
            $hash = password_hash($pword, PASSWORD_BCRYPT);
            // insert new record to table
            $insert = "INSERT INTO users (firstname, lastname, email, password, gender, address, county) 
            VALUES ('$fname', '$lname', '$email', '$hash', '$gender', '$address', '$county')";

            // inform user that their account was created
            if (mysqli_query($db_server, $insert)){
                echo "Success: User account created. Click below to login";
            }
        }
    }
}

?>