<?php session_start(); ?>

<?php
require_once 'my_api.php';
?>

<?php

function validate() {
    if (empty($_POST["username"])) {        
        return array(FALSE, "Username required!");
    }
    if (empty($_POST["password"])) {
        return array(FALSE, "Password required!");
    }
    if (empty($_POST["password2"])) {
        return array(FALSE, "Password2 required!");
    }
    if ($_POST["password"] != $_POST["password2"]) {
        return array(FALSE, "Password and Password2 must match!");
    }
    return array(TRUE, "");
}

// Main starts here
$errstr = "";

$user = "";
$pass = "";
$pass2 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = validate();
    $errstr = $valid[1];
    if ($valid[0]) {
        $user = $_POST["username"];
        $pass = $_POST["password"];
        $pass2 = $_POST["password2"];

        if (check_exists($user)) {
            $errstr = "Username allready exists";
        } else {
            if (register($user, $pass)) {
                if (have_callback_uri()) {
                    call_callback_uri();
                } else {
                    header("location:index.php");
                }
            } else {
                $errstr = "Registration failed!";
            }
        }
    } else {
        $user = $_POST["username"];
    }
}
?>


<?php
include 'header.php';
 ?>

<div id="login-form">
    <form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <fieldset>
            <legend>Register Form</legend>
            <span class="error-message"><?php echo $errstr ?></span><br>
            <label for="username">User:</label>
            <input type="text" name="username" value="<?php echo $user; ?>" placeholder="user@domain.ext" autofocus/>
            <br />
            <label for="password">Pass:</label>
            <input type="password" name="password"/>
            <br />
            <label for="password2">Pass2:</label>
            <input type="password" name="password2"/>
            <br />
            <p style="text-align: center;">
            <input type="submit" value="Register"/>
            </p>            
        </fieldset>
    </form>
</div>

<?php
include 'footer.php';
 ?>