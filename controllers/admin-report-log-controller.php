<?php

// ----- Sample Reports Data -----
$reports = [
    [
        "id" => "01",
        "name" => "Resident #1",
        "area" => "Mandalagan",
        "status" => "No Response",
        "last_updated" => "11-11-11"
    ],
    [
        "id" => "02",
        "name" => "Resident #2",
        "area" => "Brgy Mandalagan",
        "status" => "Danger",
        "last_updated" => "11-11-11"
    ],
    [
        "id" => "03",
        "name" => "Resident #3",
        "area" => "Brgy Mandalagan",
        "status" => "Alert",
        "last_updated" => "11-11-11"
    ],
    [
        "id" => "04",
        "name" => "Resident #4",
        "area" => "Brgy Mandalagan",
        "status" => "Alert",
        "last_updated" => "11-11-11"
    ]
];

function getBadgeClass($status) {
    switch ($status) {
        case "No Response":
            return "badge-no-response";

        case "Danger":
            return "badge-danger";

        case "Alert":
            return "badge-alert";

        case "Safe":
            return "badge-safe";

        default:
            return "";
    }
}

?>
