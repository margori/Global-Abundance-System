<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */


	echo '<p>';
	// Home ----------------------
	if ($pageKey == 'Home')
		echo  '<b><a href="index.php">' . _('Home') . '</a></b> ';
	else
		echo  '<a href="index.php">' . _('Home') . '</a> ';
		
	// Items ----------------------
	if ($pageKey == 'Requests')
		echo  '<b><a href="requests.php">' . _('Requests') . '</a></b> ';
	else
		echo  '<a href="requests.php">' . _('Requests') . '</a> ';

	// Technologies ----------------------
	if ($pageKey == 'Technologies')
		echo  '<b><a href="technologies.php">' . _('Technologies') . '</a></b> ';
	else
		echo  '<a href="technologies.php">' . _('Technologies') . '</a> ';

	// Warehouses ----------------------
	if ($pageKey == 'Warehouses')
		echo  '<b><a href="warehouses.php">' . _('Warehouses') . '</a></b> ';
	else
		echo  '<a href="warehouses.php">' . _('Warehouses') . '</a> ';

	// Items ----------------------
	if ($pageKey == 'Items')
		echo  '<b><a href="items.php">' . _('Items') . '</a></b> ';
	else
		echo  '<a href="items.php">' . _('Items') . '</a> ';

	// Producers ----------------------
	if ($pageKey == 'Producers')
		echo  '<b><a href="producers.php">' . _('Producers') . '</a></b> ';
	else
		echo  '<a href="producers.php">' . _('Producers') . '</a> ';

	echo '</p>';
?>

