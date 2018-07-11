<?php

// check if user is already logged in - if so redirrect them
if(isset($_COOKIE['user'])){
    header("Location: home.php");
}
?>

<html>
    <head>
        <title>Login Form</title>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="style/style.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#form").submit(function(event) {
                    // prevent form from submitting itself
                    event.preventDefault();
                    // variable to store the email address and password
                    var email = $("#email").val();
                    var pword = $("#pword").val();
                    // check if either field is empty - if so show the error message
                    if(email == "" || pword == ""){
                        $("#error-field").removeClass("error-hidden");
                        $("#error-field").addClass("error-show");
                        $("#error-field").text("One or more fields are empty");
                    }
                    else{
                        // convert the data into a JSON object
                        var form_data = JSON.stringify({"email": email, "pword": pword});
                        // AJAX request to send JSON object to the server for validation
                        $.ajax({
                            url: $("#form").attr('action'),
                            type: $("#form").attr('method'),
                            data: { "data": form_data }
                        }).done(function (result) {
                            // if "Success" returned then login was successful, redirect the user to home.php
                            if(result == "Success"){
                                //$("#error-field").removeClass("error-show");
                                //$("#error-field").addClass("error-hidden");
                                //$("#error-field").html("");
                                $(location).attr('href', 'home.php');
                            }
                            // else if "Fail" returned then login failed, inform the user
                            else if (result == "Fail") {
                                $("#error-field").removeClass("error-hidden");
                                $("#error-field").addClass("error-show");
                                $("#error-field").html("Email address or password not correct");
                            }
                        })
                        }
                });
            });
        </script>
    </head>
    <body>
        <h2>Login Form</h2>
        <form id="form" method="post" action="loginUser.php">
            <label for="email">Email address </label><input type="text" id="email">
            <br><label for="pword">Password </label><input type="password" id="pword">
            <br><label id="error-field" class="error-hidden">*</label>
            <br><input type="submit" value="Login">
            <br><a href="register.php">Not a member? Click here to register.</a>
        </form>
    </body>
</html>