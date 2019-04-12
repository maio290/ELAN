<?php

namespace ELAN;
require_once(__DIR__ . '/vendor/autoload.php');

if (isset($_SESSION)) {
    header("Location: dashboard.php");
    die();
}

echo HeaderView::generate("ELAN - Login");
?>

<div class="hideOverflow">

    <div class="loginContainer">
        <?PHP
        if($_GET)
        {
            if(isset($_GET['errno']))
            {
                echo '<div class="informationBar red">'.MessageKeyResolver::getErrorStringForErrno($_GET['errno']).'</div></br>';
            }
            if(isset($_GET['info']))
            {
                echo '<div class="informationBar green">'.MessageKeyResolver::getSuccessStringForKey($_GET['info']).'</div></br>';
            }
        }
        ?>
        <img src="img/company.png" width="200rem"/>
        <br>
        <form action="src/actions/login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Passwort</label>
            <input type="password" name="password" id="password" required>
            <br><br>
            <input type="submit" value="Anmelden">
        </form>
    </div>
</div>