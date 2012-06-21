<?php

ob_start();
	
	require('_config/config.php');
	require('_config/functions.php');
	require('_config/Database.singleton.php');

	$db = Database::obtain(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
	$db->connect();
	
		$page = sanitize($_GET['page']);
		$doit = (empty($page) ? '/' : $page);
			
		if($doit == '/'):
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
	<link rel="stylesheet" href="css/main.css" type="text/css" />
</head>
<body>

		<h1><?=SITE_URL;?></h1>
		
			<p class="about">This is a URL Shortner developed by <a href="http://andrewchamp.com/">Andrew Champ</a>.</p>

</body>
</html>
<?php
		else:
			redirectURL();
		endif;
		
	$db->close();
	
ob_flush();

?>