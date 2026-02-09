<?php
// ===== Map Page Data =====

// Page header content
$pageTitle = "Map";
$pageDescription = "Current Flood Forecast to get an idea of current flooding";

// Legend Items Array
$legendItems = [
    [
        "icon" => "🌊",
        "color" => "blue",
        "title" => "Flooded Areas",
        "desc" => "Areas currently affected by flooding (color coded)"
    ],
    [
        "icon" => "⚠️",
        "color" => "orange",
        "title" => "High Risk Zones",
        "desc" => "Likely to flood or already experiencing deep water"
    ],
    [
        "icon" => "☔",
        "color" => "gray",
        "title" => "Heavy Rainfall",
        "desc" => "Areas with ongoing or expected heavy rain"
    ],
    [
        "icon" => "📈",
        "color" => "blue",
        "title" => "Water Level Rising",
        "desc" => "Rivers or streets with increasing water levels"
    ],
    [
        "icon" => "🏠",
        "color" => "red",
        "title" => "Evacuation Centers",
        "desc" => "Safe locations for temporary shelter"
    ],
    [
        "icon" => "🚫",
        "color" => "red",
        "title" => "Road Closures",
        "desc" => "Roads not passable due to flooding"
    ],
    [
        "icon" => "📍",
        "color" => "green",
        "title" => "Your Location",
        "desc" => "Shows where you are on the map"
    ],
    [
        "icon" => "🕒",
        "color" => "gray",
        "title" => "Last Updated",
        "desc" => "Time of the latest map update"
    ]
];

// Map image path
$mapImage = "../images/placeholderlngdnay.png";

?>
