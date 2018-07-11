<html>
    <head>
        <title>Update Password</title>
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
require_once 'db_login.php';
include 'functions.php';

$dbpword;                               // the password of user retrieved from database
$pword;                                 // password entered
$pwordError = "";                       // show any errors associated with the password
$validPword = true;                     // used to determine whether or not password is valid for update
$updateMsg = "";                        // the message shown to the user when an update is performed

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
        // call function to get the current password of the user
        $dbpword = lookupUser($db_server, $userId, "password");
    }

    // if POST event
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // get the new password entered
        $pword = $_POST["pword"];
        // check that any of the three passwords are empty
        if (empty($_POST["oldpword"]) || empty($pword) || empty($_POST["repword"])) {
            $validPword = false;
            $pwordError = "Please ensure all fields are complete " . "<br>";
        }
        // check that the old password entered is the same as the one stored in the database
        if ((!empty($_POST["oldpword"])) && (!password_verify($_POST['oldpword'], $dbpword))) {
            $validPword = false;
            $pwordError = $pwordError . "Password entered does not match current password " . "<br>";
        }
        // check that the new password is not empty
        if (!empty($pword)) {
            // check that the new password is different from the old one
            if (password_verify($pword, $dbpword)){
                $validPword = false;
                $pwordError = $pwordError . "Please enter a different password to the current one ";
            }
            else {
                // check that password contains at least 8 characters
                if (strlen($pword) < 8) {
                    $validPword = false;
                    $pwordError = $pwordError . "Password must contain at least 8 characters " . "<br>";
                }
                // check that password contains at least 1 number
                if (!preg_match("/[0-9]/", $pword)) {
                    $validPword = false;
                    $pwordError = $pwordError . "Password must contain at least one number " . "<br>";
                }
                // check that password contains at least 1 letter
                if ((!preg_match("/[A-Z]/", $pword)) || (!preg_match("/[a-z]/", $pword))) {
                    $validPword = false;
                    $pwordError = $pwordError . "Password must contain at least one uppercase and one lowercase letter " . "<br>";
                }
                if (!preg_match("/[^\da-zA-Z]/", $pword)) {
                    $validPword = false;
                    $pwordError = $pwordError . "Password must contain at least one special character " . "<br>";
                }
                // check that new password is the same as confirm password
                if ($pword != $_POST["repword"]) {
                    $validPword = false;
                    $pwordError = $pwordError . "Please ensure you have re-entered your new password correctly ";
                }
            }

        }
        // if no errors then update can be performed
        if($validPword){
                // hash the password using Bcrypt and update the password using this hash
                $hash = password_hash($pword, PASSWORD_BCRYPT);
                $updatePassword = "UPDATE users SET password = '$hash' WHERE userId = '$userId'";
                // notify the user that their password has been updated
                if (mysqli_query($db_server, $updatePassword)) {
                    $updateMsg = "Password updated successfully";
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
    <h2>Update Password</h2>
    <form id="updatePassword" method="post">
        <label for="oldpword">Enter old password</label>
        <input type="password" id="oldpword" name="oldpword">
        <br><label for="pword">Enter new password</label>
        <input type="password" id="pword" name="pword">
        <br><label for="repword">Confirm new password</label>
        <input type="password" id="repword" name="repword">
        <br><label id="repword-error" class="error-show"><?php echo $pwordError ?></label>
        <br><label class="db-update"><?php echo $updateMsg; ?></label>
        <br><input type="submit" value="Update Password">
    </form>
    </body>
</html>

