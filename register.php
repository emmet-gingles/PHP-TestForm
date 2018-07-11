
<html>
    <head>
        <title>Registration Form</title>
        <meta charset="UTF-8">
        <link type="text/css" rel="stylesheet" href="style/style.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="scripts/functions.js"></script>
        <script>
            $(document).ready(function() {
                        // call functio to load list of counties into drop down menu
                        loadCounties('#county');
                        $("#form").submit(function(event) {
                            // prevent form from submitting itself
                            event.preventDefault();
                            // variables for storing each part of the form data
                            var fname = $("#fname").val();
                            var lname = $("#lname").val();
                            var email = $("#email").val();
                            var pword = $("#pword").val();
                            var repword = $("#repword").val();
                            var gender = $("input:radio[name=gender]:checked").val();
                            var county = $("#county").val();
                            var address = $("#address").val();
                            // variable that checks that everything is correct on the client-side before it is sent to the server
                            var validForm = true;
                            // check firstname is not an empty string
                            if(fname == ""){
                                $("#fname-error").removeClass("error-hidden");
                                $("#fname-error").addClass("error-show");
                                $("#fname-error").html("* Required Field");
                                validForm = false;
                            }
                            else{
                                // call the function to validate firstname - show error if necessary
                                msg = checkText(fname, 2, "Firstname");
                                if (msg != ""){
                                    $("#fname-error").html(msg);
                                    validForm = false;
                                }
                                else{
                                    $("#fname-error").removeClass("error-show");
                                    $("#fname-error").html("");
                                }
                            }
                            // check lastname
                            if(lname == ""){
                                $("#lname-error").removeClass("error-hidden");
                                $("#lname-error").addClass("error-show");
                                $("#lname-error").html("* Required Field");
                                validForm = false;
                            }
                            else{
                                msg = checkText(lname, 2, "Lastname");
                                if (msg != ""){
                                    $("#lname-error").html(msg);
                                    validForm = false;
                                }
                                else{
                                    $("#lname-error").removeClass("error-show");
                                    $("#lname-error").html("");
                                }
                            }
                            // check email address
                            if(email == "") {
                                $("#email-error").removeClass("error-hidden");
                                $("#email-error").addClass("error-show");
                                $("#email-error").html("* Required Field");
                                validForm = false;
                            }
                            else{
                                // call function to validate email - show error if appropriate
                                var msg = checkEmail(email);
                                if(!msg){
                                    // change the height of the div to ensure it is big enough for the whole message
                                    $('div.email').css('height','80px');
                                    $("#email-error").removeClass("error-hidden");
                                    $("#email-error").addClass("error-show");
                                    $("#email-error").html("Email address is not valid. <br> Please ensure it is in the following format." +
                                        "<br>The local part, followed by @, followed by the domain name. ");
                                    validForm = false;
                                }
                                else{
                                    $("#email-error").removeClass("error-show");
                                    $("#email-error").html("");
                                }
                            }
                            // check password
                            if(pword == ""){
                                $("#pword-error").removeClass("error-hidden");
                                $("#pword-error").addClass("error-show");
                                $("#pword-error").html("* Required Field");
                                validForm = false;
                            }
                            else{
                                // call function to validate password
                                msg = checkPassword(pword);
                                if(msg != ""){
                                    // counts the number of <br> in the message to calculate the total lines
                                    countLines = msg.match(/<br>/igm), countLines = (countLines) ? countLines.length : 0;
                                    countLines = countLines + 1;
                                    // depending on the number of lines, adjust the div height
                                    if(countLines == 3){
                                        $('div.password').css('height','80px');
                                    }
                                    else if(countLines == 4){
                                        $('div.password').css('height','100px');
                                    }
                                    $("#pword-error").html(msg);
                                    validForm = false;
                                }
                                else{
                                    $("#pword-error").removeClass("error-show");
                                    $("#pword-error").html("");
                                    // check password and confirm password are equal
                                    if(pword != repword){
                                        $("#repword-error").removeClass("error-hidden");
                                        $("#repword-error").addClass("error-show");
                                        $("#repword-error").html("Password fields are not equal");
                                        validForm = false;
                                    }
                                    else{
                                        $("#repword-error").removeClass("error-show");
                                        $("#repword-error").html("");
                                    }
                                }
                            }
                            // check address
                            if(address == ""){
                                $("#address-error").removeClass("error-hidden");
                                $("#address-error").addClass("error-show");
                                $("#address-error").text("* Required Field");
                                validForm = false;
                            }
                            else{
                                // call function to validate address
                                var msg = checkAddress(address);
                                if (msg != ""){
                                    // change div height so that it can display full message
                                    $('div.address').css('height','80px');
                                    $("#address-error").removeClass("error-hidden");
                                    $("#address-error").addClass("error-show");
                                    $("#address-error").html(msg);
                                    validForm = false;
                                }
                                else{
                                    $("#address-error").removeClass("error-show");
                                    $("#address-error").text("");
                                }
                            }
                            // if no errors occurred
                            if(validForm) {
                                // convert form data to a JSON object
                                var form_data = JSON.stringify({"fname": fname, "lname": lname, "email": email, "pword": pword, "gender": gender,
                                    "county": county, "address": address});
                                // AJAX request to send JSON object to the server for validation
                                $.ajax({
                                    url: $("#form").attr('action'),
                                    type: $("#form").attr('method'),
                                    data: { "data": form_data }
                                }).done(function (result) {
                                    // if an error occurred show error message in red text
                                    if(result.startsWith("Error")){
                                        $("#resultMessage").css("color", "red");
                                        $("#resultMessage").html(result.substr(6));
                                    }
                                    // else account was created, inform the user with green text
                                    else if(result.startsWith("Success")){
                                        $("#resultMessage").css("color", "green");
                                        $("#resultMessage").html(result.substr(8));
                                    }
                                })
                            }
                        });
            });
        </script>

    </head>
    <body>
        <h2>Registration Form</h2>
        <form id="form" method="post" action="createAccount.php" >
            <div>
                <label for="fname">First name </label>
                <input type="text" id="fname">
                <br><label id="fname-error" class="error-hidden">*</label>
            </div>
            <div>
                <label for="lname">Last name </label>
                <input type="text" id="lname">
                <br><label id="lname-error" class="error-hidden">*</label>
            </div>
            <div class="email">
                <label for="email">Email address </label>
                <input type="text" id="email">
                <br><label id="email-error" class="error-hidden">*</label>
            </div>
            <div class="password">
                <label for="pword">Password </label>
                <input type="password" id="pword">
                <br><label id="pword-error" class="error-hidden">*</label>
            </div>
            <div>
                <label for="repword">Retype password </label>
                <input type="password" id="repword">
                <br><label id="repword-error" class="error-hidden">*</label>
            </div>
            <div>
                <label>Gender</label>
                <span class="radio-buttons">
                    <input type="radio" name="gender" value="Male" checked="checked">Male
                    <input type="radio" name="gender" value="Female">Female
                </span>
                <br><label id="gender-error" class="error-hidden">*</label>
            </div>
            <div>
                <label>County</label>
                <select id="county"></select>
            </div>
            <div class="address">
                <label for="address">Home address </label>
                <textarea id="address"></textarea>
                <br><br><br><label id="address-error" class="error-hidden">*</label>
            </div>
            <br><input type="submit" value="Register">
            <br> <span id="resultMessage"></span>
            <br><a href="login.php">Already a member? Click here to login.</a>
        </form>
    </body>
</html>