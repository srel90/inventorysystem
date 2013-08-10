<?php
session_start();
unset($_SESSION['users']);
unset($_SESSION['gridTemp']);
unset($_SESSION['gridTempPurchase']);
header("location:index.php");
?>