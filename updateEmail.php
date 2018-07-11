<html>
    <head>
        <title>Update Email</title>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="style/style.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="scripts/functions.js"></script>
        <script>
            $(document).ready(function() {
                $(function () {
                    // load banner links from html file
                    $("#banner").load("template/banner.html");
                });
            });
        </script>
    </head>
    <body>
<?php
// php file used for database connection parameters
require_once 'db_login.php';
// php file used for calling functions
include 'functions.php';

$db_email;                  // the email of user retrieved from database
$email = "";                // the email entered
$emailChanged = false;      // used to determine whether or not the initial email has been changed
$emailError = "";           // show any errors associated with the email
$validEmail = true;         // used to determine whether or not email is valid for update
$updateMsg = "";            // the message shown to the user when an update is performed

// check that user is logged in
if(isset($_COOKIE['userId'])) {

    // get the userId from cookie
    $userId = $_COOKIE['userId'];

    // try to connect using connection parameters
    $db_server = mysqli_connect($db_hostname, $db_username, $db_password);
    // terminate if connection unsuccessful
    if (!$db_server) {
        die("Unable to connect to MySQL: " . mysqli_connect_error());
    }

    // select database
    if (mysqli_select_db($db_server, $db_name)) {
        // call function to get the current email address of the user
        $db_email = lookupUser($db_server, $userId, "email");
    }

    // if POST event
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // get the email address entered
        $email = $_POST["email"];
        // check if the email address is empty
        if (empty($email) ) {
            $emailChanged = true;
            $validEmail = false;
            $emailError = "Please enter an email address ";
        }
        else{
            $emailChanged = true;
            // check the email address is valid
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validEmail = false;
                $emailError = "Please enter a valid email address";
            }
            // check if the email address entered is the same as the current email address
            elseif ($email == $db_email){
                $validEmail = false;
                $emailError = "Please enter a different email address to current email address";
            }
            else{
                // check if the email address belongs to another user - if yes then show error message
                $select = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($db_server, $select);
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0){
                    $validEmail = false;
                    $emailError = "Email address belongs to an existing user";
                }
            }
        }
        // if no errors then update email address and notify user
        if($validEmail){
            $updateEmail = "UPDATE users SET email = '$email' WHERE userId = '$userId'";
            if (mysqli_query($db_server, $updateEmail)) {
                $updateMsg = "Email address updated successfully";
            }
        }
    }

}
// else user is not logged in so redirrect them
else{
    header("Location: login.php");
}

?>
<div id="banner"></div>
<h2>Update Email Address</h2>
    <form id="updateEmail" method="post">
        <label for="email">Enter email address</label>
        <input type="text" id="email" name="email" value=<?php if(!$emailChanged) { echo $db_email; } else { echo $email; } ?> >
        <br><label id="email-error" class="error-show"><?php echo $emailError ?></label>
        <br><label id="emailChanged" name="emailChanged" class="db-update"><?php echo $updateMsg; ?></label>
        <br><input type="submit" value="Update Email Address">
    </form>
    </body>
</html>

