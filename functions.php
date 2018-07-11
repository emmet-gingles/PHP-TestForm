<?php

require_once 'db_login.php';

function lookupUser($conn, $userId, $lookupField) {
    $select = "SELECT $lookupField FROM users WHERE userId = $userId";
    $result = mysqli_query($conn, $select);
    $num_rows = mysqli_num_rows($result);

    if ($num_rows > 0) {
        $field = mysqli_fetch_row($result);
        return $field[0];
    }
    else{
        return null;
    }
}

// function that returns a list of all the counties in Ireland
function loadCounties(){
        $counties = array("Antrim", "Armagh", "Carlow", "Cavan", "Clare", "Cork", "Derry", "Donegal", "Down", "Dublin", "Fermanagh", "Galway",
            "Kerry", "Kildare", "Kilkenny", "Laois", "Leitrim", "Limerick", "Longford", "Louth", "Mayo", "Meath", "Monaghan", "Offaly",
            "Roscommon", "Sligo", "Tipperary", "Tyrone", "Waterford", "Westmeath", "Wexford", "Wicklow");
        return $counties;
}