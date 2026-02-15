<?php
$loginTitle    = "Log In";
$loginSubtitle = "Input your credentials below.";

$errorMessage = "";

$oldEmail = $_GET['email'] ?? "";

if(isset($_GET['error'])) {

    if($_GET['error'] == "invalid") {
        $errorMessage = "Invalid email or password.";
    }

    if($_GET['error'] == "empty") {
        $errorMessage = "Please fill out all fields.";
    }

}

$formAction = "../controllers/process-login.php";
$userRole   = "user";

?>
