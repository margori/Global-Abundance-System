<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

include_once 'includes/database.php';

function HasPrivilege($privilegeKey)
{	
    if ($_SESSION['temporalPrivilege'] == $privilegeKey)
				return true;

    $beingId = $_SESSION['BeingId'];

    if (!$beingId)
        return false;        

    $query = "select * from UserPrivileges up inner join Privileges p on p.PrivilegeId where ";
    return true;
}
?>
