<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');


if (isset($_POST)) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $bypassPassword = false;

            $config = new Config_Provider();
            $LDAP = new LDAP_Provider($config);
            if ($LDAP->login($_POST['username'], $_POST['password']) || $bypassPassword) {
                $displayName = $LDAP->getDisplayNameForCN($_POST['username']);
                if (!$displayName) {
                    header("Location:  ../../index.php?errno=2");
                    die();
                }
                session_start();
                $user = new User($_POST['username'], $displayName, in_array($_POST['username'], $config->admins));
                $_SESSION['user'] = $user->serialize();
                header("Location:  ../../dashboard.php");
                die();
            } else {
                header("Location: ../../index.php?errno=1");
                die();
            }
        }
    }
}