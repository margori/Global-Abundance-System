<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

		if (isset($_SESSION['Messages']))
		{
				foreach($_SESSION['Messages'] as $message)
					echo "<p style=\"color:red\">$message</p>";
				$_SESSION['Messages'] = null;
		}
?>
<p><?= _('More information in '); ?><a href="http://www.margori.com.ar/wiki">http://www.margori.com.ar/wiki</a></p>
