<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

	$pageName = 'Login page';

	include_once 'includes/common.php';
	include_once 'includes/database.php';
    
	if (isset($_POST['login']))
	{
		$username = Sanitize($_POST['username']);
		$password = md5($_POST['password']);
 		
		$sql = "select b.BeingId, b.Name as BeingName from Users u inner join Beings b on b.BeingId = u.BeingId where u.Username = '$username' and u.Password = '$password'";
		$result = DBQuery($sql);
		$count = DBCount($result);
		
		$errorMessage = '';
		if ($count != 1)
		{
			$errorMessage = "Username or password incorrect";
		}
		else
		{
			$user = DBFetch($result);
			$_SESSION['beingId'] = $user['BeingId'];
			$_SESSION['beingName'] = $user['BeingName'];		
			Redirect('index.php');
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
        <form action="#" method="post" >
            <table>
                <tr>
                    <td>
                          <?= _('Username:') ?>
                    </td>
                    <td>
                        <input type="text" name="username"/>
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Password:') ?>
                    </td>
                    <td>
                        <input type="password" name="password"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="login" value="<?= _('Login') ?>" />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="register.php" ><?= _('Register') ?></a>
                    </td>
                </tr>
            </table>
        </form>
        <?php include('includes/footer.php'); ?>
    </body>
</html>
