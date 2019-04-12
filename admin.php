<?php
require_once(__DIR__ . '/vendor/autoload.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php?errno=3");
    die();
}

if (!ELAN\User::deserialize($_SESSION['user'])->isAdmin) {
    header("Location: index.php?errno=4");
    die();
}
echo ELAN\HeaderView::generate("ELAN - Admin");
echo ELAN\NavbarController::generate($_SESSION);
echo ELAN\NotificationController::generate($_GET);
echo ELAN\AdminController::generate();