

    // function that accepts a drop down menu as input and appends each county as an option for it
    function loadCounties(menu) {
        var counties = ["Antrim", "Armagh", "Carlow", "Cavan", "Clare", "Cork", "Derry", "Donegal", "Down", "Dublin", "Fermanagh", "Galway",
            "Kerry", "Kildare", "Kilkenny", "Laois", "Leitrim", "Limerick", "Longford", "Louth", "Mayo", "Meath", "Monaghan", "Offaly",
            "Roscommon", "Sligo", "Tipperary", "Tyrone", "Waterford", "Westmeath", "Wexford", "Wicklow"];
        for (i = 0; i < counties.length; i++) {
            $(menu).append($('<option>', {value: counties[i], text: counties[i]}))
        }
    }

    // function that validates some text input
    function checkText(text, minLength, fieldType) {
        var chars = /^[A-Za-z]+$/;
        // check that text only contains letters
        if (!text.match(chars)) {
            return fieldType + " can only contain letters";
        }
        // check that text contains the minimum number of characters
        if (text.length < minLength) {
            return fieldType + " must contain at least " + minLength + " characters";
        }
        else {
            return "";
        }
    }

    // function that validates a password
    function checkPassword(password) {
        var minLength = 8;              // minimum number of characters
        var msg = "";
        var regExp = /[_\-!\"@;,.:]/;   // regular expressions that are permitted
        // check that password contains the minimum number of characters
        if (password.length < minLength) {
            msg = "Password must contain at least " + minLength + " characters";
        }
        // check that password contains at least 1 uppercase and 1 lowercase letter
        if ((!password.match(/[A-Z]/)) || (!password.match(/[a-z]/))) {
            msg = msg + "<br> Password must contain at least one uppercase and one lowercase letter";
        }
        // check that password contains at least 1 number
        if (!password.match(/\d/)) {
            msg = msg + "<br> Password must contain at least one number";
        }
        // check that password contains at least 1 regular expression
        if (!regExp.test(password)) {
            msg = msg + "<br> Password must contain at least one regular expression";
        }
        return msg;
    }

    // function that checks that the email address is valid
    function checkEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    // function that checks an address is valid
    function checkAddress(address) {
        var minLength = 5;
        var msg = "";
        // check that address has the minimum number of characters
        if (address.length < minLength) {
            msg = "Address must contain at least " + minLength + " characters";
        }
        // check that address contains at least 1 letter
        if (!address.match(/[A-z]/)) {
            msg = msg + "<br> Address must contain at least one letter";
        }
        // check that address contains at least 1 number
        if (!address.match(/\d/)) {
            msg = msg + "<br> Address must contain at least one number";
        }
        return msg;
    }

