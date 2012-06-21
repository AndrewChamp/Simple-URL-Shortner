<?php

ob_start();
	
	require('../_config/config.php');
	require('../_config/functions.php');
	require('../_config/Database.singleton.php');

	$db = Database::obtain(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
	$db->connect();
	
		if($_COOKIE['password'] != 'true'):
			logMeInFoo();
		else:
			newURL();
			deleteURL();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
	<title>Admin  |  <?=SITE_URL;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link rel="stylesheet" href="../css/main.css" type="text/css" />
</head>
<body>
		<?=errors($message);?>
		<?=makeDomain();?>		
		<?=allDomains();?>

	<script>script>window.scrollTo(0,1)</script>
</body>
</html>
<?php
		endif;
	
	$db->close();
	
ob_flush();

?>