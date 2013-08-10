<?php
ob_start();
header("Content-Type:   application/vnd.ms-excel; charset=utf-16");
header("Content-type:   application/x-msexcel; charset=utf-16");
header("Content-Disposition: attachment; filename=".$_POST['reportname'].".csv"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); 
echo  $_POST['exportdata'];
ob_end_flush();
?>