<?php
require_once(__DIR__ . '/vendor/autoload.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php?errno=3");
}

echo ELAN\HeaderView::generate("ELAN - Dashboard");
echo ELAN\NavbarController::generate($_SESSION);
echo ELAN\DashboardView::generate($_SESSION);