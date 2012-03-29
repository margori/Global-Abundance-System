<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

  include_once 'includes/common.php';
  include_once 'includes/privileges.php';

	$pageName = 'Register page';

	$requiredPrivilege = 'Register';

	if (!HasPrivilege($requiredPrivilege))
  {
		Redirect("acquirePrivilege.php?p=$requiredPrivilege");
		exit;
	}
	
	if ($_POST['register'])
	{
		$beingname = trim(Sanitize($_POST['beingname']));
		$username = trim(Sanitize($_POST['username']));
		$password = Sanitize($_POST['password']);
		$confirm =  Sanitize($_POST['confirm']);
		
		$errorMessage = '';
		
		// Validations ---------------------
		if ($beingname == '')
			$errorMessage .= 'Your name required. ';

		if ($username == '')
			$errorMessage .= 'Username required. ';

		if ($password == '')
			$errorMessage .= 'Password required. ';

		if ($confirm == '')
			$errorMessage .= 'Password confirmation required. ';

		if ($password != $confirm)
			$errorMessage .= 'Password and confirmation are distinct. ';
			
		if ($beingname != '' && $username != '' && $password != '' && $password == $confirm)
		{
			$password = md5($password);

			$sql = "select count(*) as count from Beings b where b.Name = '$beingname'";
			$result = DBQuery($sql);
			$count = DBFetch($result);
					
			if ($count['count'] != 0)
				$errorMessage .= 'Name already taken.';

			$sql = "select count(*) as count from Users u where Username = '$username'";
			$result = DBQuery($sql);
			$count = DBFetch($result);
					
			if ($count['count'] != 0)
				$errorMessage .= 'Username already taken.';
			
			// End of validations ---------------------

			if ($errorMessage == '')
			{
				$sql = "insert into Beings (Name) values ('$beingname')";
				DBQuery($sql);
				$beingId = DBid();
				
				$sql = "insert into Users (BeingId, UserName, Password) values ($beingId, '$username', '$password')";
				DBQuery($sql);
				
				$sql = "insert into UserPrivileges (BeingId, PrivilegeId) values ($beingId, (select PrivilegeId from Privileges p where p.PrivilegeKey = 'Register'))";
				DBQuery($sql);		
				
				$_SESSION['beingId'] = $beingId;
				$_SESSION['beingNanem'] = $beingname;
				
				Redirect('index.php');
			}
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php include('includes/header.php'); ?>
        <form action="#" method="post">
            <table>
                <tr>
                    <td>
                          <?= _('Your name:') ?>
                    </td>
                    <td>
                        <input type="text" name="beingname" value="<?= $beingname ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Username:') ?>
                    </td>
                    <td>
                        <input type="text" name="username" value="<?= $username ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Password:') ?>
                    </td>
                    <td>
                        <input type="password" name="password" />
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Confirm Password:') ?>
                    </td>
                    <td>
                        <input type="password" name="confirm" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="register" value="<?= _('Register') ?>" />
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>

