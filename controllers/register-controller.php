<?php
$registerTitle = "Register Account";

$formAction = "../controllers/process-register.php";

$barangays = [
    "Barangay 1", "Barangay 2", "Barangay 3", "Barangay 4", "Barangay 5",
    "Barangay 6", "Barangay 7", "Barangay 8", "Barangay 9", "Barangay 10",
    "Barangay 11", "Barangay 12", "Barangay 13", "Barangay 14", "Barangay 15",
    "Barangay 16", "Barangay 17", "Barangay 18", "Barangay 19", "Barangay 20",
    "Barangay 21", "Barangay 22", "Barangay 23", "Barangay 24", "Barangay 25",
    "Barangay 26", "Barangay 27", "Barangay 28", "Barangay 29", "Barangay 30",
    "Barangay 31", "Barangay 32", "Barangay 33", "Barangay 34", "Barangay 35",
    "Barangay 36", "Barangay 37", "Barangay 38", "Barangay 39", "Barangay 40",
    "Barangay 41", "Alangilan", "Alijis", "Banago", "Bata", "Cabug",
    "Estefania", "Felisa", "Granada", "Handumanan", "Mandalagan", "Mansilingan",
    "Montevista", "Pahanocoy", "Punta Taytay", "Singcang-Airport", "Sum-ag",
    "Taculing", "Tangub", "Villamonte", "Vista Alegre"
];

$old = [
    'first_name' => $_GET['first_name'] ?? "",
    'last_name'  => $_GET['last_name'] ?? "",
    'email'      => $_GET['email'] ?? "",
    'phone'      => $_GET['phone'] ?? "",
    'address'    => $_GET['address'] ?? "",
    'specific_address' => $_GET['specific_address'] ?? ""
];

$errors = [
    'first_name'      => "",
    'last_name'       => "",
    'email'           => "",
    'phone'           => "",
    'address'         => "",
    'specific_address'=> "",
    'password'        => "",
    'confirm_password'=> ""
];

if(isset($_GET['error'])) {

    switch($_GET['error']) {

        case "empty":
            // missing one or more required fields
            $errors['first_name'] = "Please fill out all fields.";
            $errors['last_name']  = "Please fill out all fields.";
            $errors['email']      = "Please fill out all fields.";
            $errors['phone']      = "Please fill out all fields.";
            $errors['address']    = "Please fill out all fields.";
            break;

        case "name":
            // invalid characters in either first or last name
            $errors['first_name'] = "Names must contain letters only.";
            $errors['last_name']  = "Names must contain letters only.";
            break;

        case "email":
            $errors['email'] = "Invalid email format.";
            break;

        case "phone":
            $errors['phone'] = "Invalid PH phone number.";
            break;

        case "address":
            $errors['address'] = "Address too short.";
            break;

        case "passlength":
            $errors['password'] = "Password must be at least 8 characters.";
            break;

        case "passweak":
            $errors['password'] = "Must contain uppercase, lowercase and number.";
            break;

        case "passmatch":
            $errors['confirm_password'] = "Passwords do not match.";
            break;

        case "emailtaken":
            $errors['email'] = "Email address already registered.";
            break;

        case "db":
            // generic database error, show at top? could reuse email field
            $errors['email'] = "An internal error occurred. Please try again later.";
            break;

    }

}


$successMessage = "";

if(isset($_GET['success'])) {
    $successMessage = "Registration successful! You can now login.";
}

?>
