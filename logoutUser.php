<?php
# if accessed by a user not logged in then redirect to login page
if((!isset($_COOKIE['user'])) && !isset($_COOKIE['userId'])){
    header("Location: login.php");
}
# else delete the user and userId cookies
else{
    setcookie("user", "", time() - 3600);
    setcookie("userId", "", time() - 3600);
}

?>

