<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

include_once 'config.php';

function DBConnect()
{
    global $DB, $Configuration;

    if ($DbConnected)
        return;

    $DB['DBConnection'] = mysql_connect($Configuration['DBServer'], $Configuration['DBUsername'], $Configuration['DBPassword']) or die(mysql_error());
    mysql_set_charset('utf8') or die(mysql_error()); 
	mysql_select_db($Configuration['DBName']) or die(mysql_error()); 
}

function DBQuery($query)
{
	global $DB;
    if (!$DB['DBConnection'])
        DBConnect ();
    
 	$result = mysql_query($query) or die(mysql_error());
    return $result;
}

function DBFetch($resource)
{
	return mysql_fetch_array($resource);
}

function DBCount($resource)
{
	return mysql_num_rows($resource);
}

function DBid()
{
	return 	mysql_insert_id();
}
?>
