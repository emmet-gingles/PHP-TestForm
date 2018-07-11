<html>
<head>
    <title>Update Address</title>
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

$db_address;                    // the address of user retrieved from database
$db_county;                     // the county of user retrieved from database
$address;                       // address entered
$county;                        // county selected
$addressChanged = false;        // used to determine whether or not the initial address has been changed
$countyChanged = false;         // used to determine whether or not the initial county has been changed
$addressError = "";             // show any errors associated with the address
$validAddress = true;           // used to determine whether or not address is valid for update
$updateMsg = "";                // the message shown to the user when an update is performed

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
        // call function to get the current address and county of the user
        $db_address = lookupUser($db_server, $userId, "address");
        $db_county = lookupUser($db_server, $userId, "county");
    }

    // if POST event
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // get the address entered
        $address = $_POST["address"];
        // get the county selected
        $county = $_POST["county"];
        // check if the address and county are the same as their current values
        if(($address == $db_address) && ($county == $db_county)){
            $validAddress = false;
            $addressError = "Please enter a different county or address to current values";
        }
        // check if the county has been changed
        if($county != $db_county){
            $countyChanged = true;

        }
        // check if the address is empty
        if (empty($address) ) {
            $addressChanged = true;
            $validAddress = false;
            $addressError = "Please enter an address ";
        }
        else{
            // check if the address has been changed
            if ($address != $db_address) {
                $addressChanged = true;
                // check address contains at least 5 characters
                if (strlen($address) < 5) {
                    $validAddress = false;
                    $addressError = "Address must contain at least 5 characters " . "<br>";
                }
                // check address contains at least 1 letter
                if (!preg_match("#[A-z]+#", $address)) {
                    $validAddress = false;
                    $addressError = $addressError . "Address must contain at least one letter " . "<br>";
                }
                // check address contains at least 1 number
                if (!preg_match("#[0-9]+#", $address)) {
                    $validAddress = false;
                    $addressError = $addressError . "Address must contain at least one number " . "<br>";
                }
            }
        }
        // if no errors then update can be performed
        if($validAddress){
            // if both the address and county have changed then update both fields
            if(($addressChanged) && ($countyChanged)) {
                $updateAddress = "UPDATE users SET address = '$address', county = '$county' WHERE userId = '$userId'";
            }
            // if just the address has been changed then update address
            elseif($addressChanged){
                $updateAddress = "UPDATE users SET address = '$address' WHERE userId = '$userId'";
            }
            // if just the county has been changed then update county
            elseif($countyChanged){
                $updateAddress = "UPDATE users SET county = '$county' WHERE userId = '$userId'";
            }
            // notify user of update
            if (mysqli_query($db_server, $updateAddress)) {
                $updateMsg = "Address updated successfully";
            }

        }
    }

}
// else user is not logged in so redirrect them
else{
    header("Location: login.php");
}

// call function to get list of counties
$counties = loadCounties();
// check if the original county has been changed - if yes then change the value of countyVal
if ($countyChanged){
    $countyVal = $county ;
}
// if not then set it to value retrieved from database
else{
    $countyVal = $db_county;
}

?>
<div id="banner"></div>
<h2>Update Address</h2>
<form id="updateAddress" method="post">
    <label for="county">County</label>
    <select id="county" name="county">
        <!-- loop through each county and create an option for it with the appropriate value-->
        <!-- if countyVal is equal to the county then select this option -->
        <?php foreach ($counties as $count ){ ?>
        <option value=<?php echo $count ?> <?php if ($countyVal == $count){ echo 'selected="selected"'; } ?> > <?php echo $count; } ?>
        </option>
    </select>
    <br><br><br><label for="address">Home address </label>
    <textarea id="address" name="address"><?php if (!$addressChanged) { echo $db_address; } else { echo $address; }  ?></textarea>
    <br><br><br><label id="address-error" class="error-show"><?php echo $addressError; ?></label>
    <br><label class="db-update"><?php echo $updateMsg; ?></label>
    <br><input type="submit" value="Update Address">
</form>
</body>
</html>

