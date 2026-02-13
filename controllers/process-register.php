<?php
$first    = trim($_POST['first_name'] ?? "");
$last     = trim($_POST['last_name'] ?? "");
$email    = trim($_POST['email'] ?? "");
$phone    = trim($_POST['phone'] ?? "");
$addr     = trim($_POST['address'] ?? "");
$pass     = trim($_POST['password'] ?? "");
$confirm  = trim($_POST['confirm_password'] ?? "");

if($first=="" || $last=="" || $email=="" ||
   $phone=="" || $addr=="" || $pass=="" || $confirm=="") {

    header("Location: ../views/register-user.php?error=empty");
    exit();
}

if(!preg_match("/^[a-zA-Z ]+$/", $first) ||
   !preg_match("/^[a-zA-Z ]+$/", $last)) {

    header("Location: ../views/register-user.php?error=name");
    exit();
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    header("Location: ../views/register-user.php?error=email");
    exit();
}

if(!preg_match("/^(09|\+639)[0-9]{9}$/", $phone)) {

    header("Location: ../views/register-user.php?error=phone");
    exit();
}

if($pass !== $confirm) {
    header("Location: ../views/register-user.php?error=passmatch");
    exit();
}

if(strlen($pass) < 8) {

    header("Location: ../views/register-user.php?error=passlength");
    exit();
}

if(!preg_match("/[A-Z]/", $pass) ||
   !preg_match("/[a-z]/", $pass) ||
   !preg_match("/[0-9]/", $pass)) {

    header("Location: ../views/register-user.php?error=passweak");
    exit();
}

$first = htmlspecialchars($first);
$last  = htmlspecialchars($last);
$email = htmlspecialchars($email);
$addr  = htmlspecialchars($addr);


// --------- (NEXT TIME) DATABASE ---------

header("Location: ../views/register-user.php?success=created");
exit();

?>
