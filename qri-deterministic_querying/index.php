<?php
$username = "test";
$password = "test";
$nonsense = "supercalifragilisticexpialidocious";

if (isset($_COOKIE['PrivatePageLogin'])) {
    if ($_COOKIE['PrivatePageLogin'] == md5($password.$nonsense)) {
        include_once("./v2.html");
         exit;
    } else {
        echo "Bad Cookie.";
        exit;
    }
}

if (isset($_GET['p']) && $_GET['p'] == "login") {
    if ($_POST['user'] != $username) {
        echo "Sorry, that username does not match.";
        exit;
    } else if ($_POST['keypass'] != $password) {
        echo "Sorry, that password does not match.";
        exit;
    } else if ($_POST['user'] == $username && $_POST['keypass'] == $password) {
        setcookie('PrivatePageLogin', md5($_POST['keypass'].$nonsense));
        header("Location: $_SERVER[PHP_SELF]");
    } else {
        echo "Sorry, you could not be logged in at this time.";
    }
}
?>
