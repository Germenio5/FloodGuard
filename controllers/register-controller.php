<?php
$registerTitle = "Register Account";

$formAction = "../controllers/process-register.php";

$old = [
    'first_name' => $_GET['first_name'] ?? "",
    'last_name'  => $_GET['last_name'] ?? "",
    'email'      => $_GET['email'] ?? "",
    'phone'      => $_GET['phone'] ?? "",
    'address'    => $_GET['address'] ?? ""
];

$errors = [
    'first_name'      => "",
    'last_name'       => "",
    'email'           => "",
    'phone'           => "",
    'address'         => "",
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
