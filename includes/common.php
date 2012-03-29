<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

	session_start();
	
	error_reporting(E_ALL ^ E_NOTICE);

	$locale = $_SESSION['locale'] ? $_SESSION['locale'] : "en";
	if (isSet($_GET["locale"])) 
	{
		$locale = $_GET["locale"];
		$_SESSION['locale'] = $locale;
	}
	putenv("LC_ALL=$locale.UTF-8");
	setlocale(LC_ALL, $locale . '.UTF-8');
	bindtextdomain("gas", "locale");
	textdomain("gas");
	
function Redirect($url)
{
    echo "<meta http-equiv=\"REFRESH\" content=\"0;url=$url\">";
    exit;
}

function Sanitize($s)
{
	return addslashes($s);
}
?>
