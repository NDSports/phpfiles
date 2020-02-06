<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include("/var/www/htmlphp/api/hamstring/config/constant.php"); 
include("/var/www/htmlphp/api/hamstring/class/class.mysqli.php");
include("/var/www/htmlphp/api/hamstring/class/class.functions.php"); 
session_start();
$errorMsg = "";
$objFunctions = new Functions();
if(isset($_POST["sub"])) {
	$result = $objFunctions->doMySQLBackup();
	$result = $objFunctions->doDirectoryBackup();
	//echo "MySql Backup : ".$result;exit;
	echo "MySQL Backup: Complete.</br>".
		"Directory Backup: Complete.";
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title>MySql Backup</title>
</head>
<body>
  <form name="input" action="" method="post">
    <div class="error"><?= $errorMsg ?></div>
    <input type="submit" value="Backup NOW" name="sub" />
  </form>
</body>
</html>