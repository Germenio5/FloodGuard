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

        case "first":
            $errors['first_name'] = "First name must contain letters only.";
            break;

        case "last":
            $errors['last_name'] = "Last name must contain letters only.";
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

    }

}


$successMessage = "";

if(isset($_GET['success'])) {
    $successMessage = "Registration successful! You can now login.";
}

?>
