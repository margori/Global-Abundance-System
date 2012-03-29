<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */
?>
<h1><?= _('Welcome to Global Abundance System') ?></h1>
<p>
<?php
	if (isset($_SESSION['beingName']))
		echo $_SESSION['beingName'] . ' <a href="index.php?logout">' . _('Logout') . '</a> ';
?>
<a href="index.php?locale=es_AR">Español</a> <a href="index.php?locale=en_US">English</a> </p>
<h2><?= _($pageName) ?></h2>
<?php
	if (isset($errorMessage))
		echo '<p style="color: red">' . $errorMessage . '</p>';
?>
