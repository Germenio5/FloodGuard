<?php

// ----- Statistics Data -----
$stats = [
    "safe" => 520,
    "danger" => 670,
    "registered" => 9102,
    "no_response" => 7112
];


// ----- Residents List (temporary array until DB) -----
$residents = [
    [
        "id" => "01",
        "name" => "Resident #1",
        "address" => "Somewhere sa Bacolod",
        "phone" => "09292284383",
        "email" => "resident1@example.com",
        "status" => "Safe"
    ],
    [
        "id" => "02",
        "name" => "Resident #2",
        "address" => "Balay ni Christian",
        "phone" => "09292284383",
        "email" => "resident2@example.com",
        "status" => "In Danger"
    ],
    [
        "id" => "03",
        "name" => "Resident #3",
        "address" => "Brgy Mandalagan",
        "phone" => "09292284383",
        "email" => "resident3@example.com",
        "status" => "Safe"
    ]
];


// ----- Helper Function for Badge -----
function getStatusClass($status) {
    return $status == "Safe" ? "status-safe" : "status-danger";
}

?>
