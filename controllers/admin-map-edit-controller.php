<?php

$pageTitle = "Map Edit";
$pageDescription = "Edit current map to keep users updated";

// Map image
$mapImage = "../assets/images/placeholderlngdnay.png";


$legendItems = [
    [
        "icon" => "🌊",
        "color" => "blue",
        "title" => "Flooded Areas",
        "description" => "Areas currently affected by flooding (color coded)"
    ],
    [
        "icon" => "⚠️",
        "color" => "orange",
        "title" => "High Risk Zones",
        "description" => "Likely to flood or already experiencing deep water"
    ],
    [
        "icon" => "☔",
        "color" => "gray",
        "title" => "Heavy Rainfall",
        "description" => "Areas with ongoing or expected heavy rain"
    ],
    [
        "icon" => "📈",
        "color" => "blue",
        "title" => "Water Level Rising",
        "description" => "Rivers or streets with increasing water levels"
    ],
    [
        "icon" => "🏠",
        "color" => "red",
        "title" => "Evacuation Centers",
        "description" => "Safe locations for temporary shelter"
    ],
    [
        "icon" => "🚫",
        "color" => "red",
        "title" => "Road Closures",
        "description" => "Roads not passable due to flooding"
    ],
    [
        "icon" => "📍",
        "color" => "green",
        "title" => "Your Location",
        "description" => "Shows where you are on the map"
    ],
    [
        "icon" => "🕒",
        "color" => "gray",
        "title" => "Last Updated",
        "description" => "Time of the latest map update"
    ]
];

// Button Links
$buttons = [
    "water_level" => "admin-edit-water-level-data.php",
    "edit_map" => "affectedareas.php"
];
?>
