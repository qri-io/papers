<?php
$username = "research";
$passhash = "1fcb5d7e140ff89ce257390b881f5c32";

if (isset($_COOKIE['PrivatePageLogin'])) {
    if ($_COOKIE['PrivatePageLogin'] == $passhash)) {
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
    } else if (md5($_POST['keypass']) != $passhash) {
        echo "Sorry, that passhash does not match.";
        exit;
    } else if ($_POST['user'] == $username && md5($_POST['keypass']) == $passhash) {
        setcookie('PrivatePageLogin', md5($_POST['keypass']));
        header("Location: $_SERVER[PHP_SELF]");
    } else {
        echo "Sorry, you could not be logged in at this time.";
    }
}
?>
<center>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?p=login" method="post">
<label><input type="text" name="user" id="user" /> Name</label><br />
<label><input type="passhash" name="keypass" id="keypass" /> Passhash</label><br />
<input type="submit" id="submit" value="Login" />
</form>
</center>