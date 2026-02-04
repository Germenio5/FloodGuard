<?php
$loginTitle    = "Log In";
$loginSubtitle = "Input your credentials below.";

$errorMessage = "";

if(isset($_GET['error'])) {

    if($_GET['error'] == "invalid") {
        $errorMessage = "Invalid email or password.";
    }

    if($_GET['error'] == "empty") {
        $errorMessage = "Please fill out all fields.";
    }

}

$formAction = "../controller/process-login.php";
$userRole   = "user";

?>
