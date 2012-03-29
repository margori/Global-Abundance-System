<?php
/* This file is deploy with license GNU Public License v3, http://opensource.org/licenses/gpl-3.0.html */

	$pageName = _('Technologies page');
	$pageKey = 'Technologies';

	include_once 'includes/common.php';
	include_once 'includes/database.php';
  
	if (!isset($_SESSION['beingId']))
	{
		Redirect('login.php');
		exit;
	}
	
	if (isset($_GET['logout']))
	{
		unset($_SESSION['beingId']);
		unset($_SESSION['beingName']);		
		Redirect('login.php');
	}
	
	$action = trim(Sanitize($_REQUEST['action']));
	if ($action != 'select' &&
		$action != 'new' &&
		$action != 'edit' &&
		$action != 'delete')
		$action = 'select';
	
	$errorMessage = '';
	
	if (isset($_POST['cancel']))
	{
		$action = 'select';
	} 
	else if (isset($_POST['save']))
	{ 
		$id = trim(Sanitize($_POST['id']));
		$name = htmlentities(utf8_decode(trim($_POST['name'])));
		$description = htmlentities(utf8_decode(trim($_POST['description'])));
		
		if ($name == '')
			$errorMessage = _('Name required');
		
		if ($errorMessage == '')	
		{
			if (isset($id) && $id != '')
				$sql = "update Technologies set `Name` = '$name', `Description` = '$description' where TechnologyId = $id";
			else
				$sql = "insert into Technologies (`Name`, `Description`) values ('$name', '$description') ";
			$technologies = DBQuery($sql);	
			Redirect('technologies.php');
		}
	}
	else if (isset($_POST['delete']))
	{ 
		$id = trim(Sanitize($_POST['id']));		
		$sql = "delete from Technologies where TechnologyId = $id";
		$technologies = DBQuery($sql);	
		Redirect('technologies.php');
	}
	
	if ($action == 'select')
	{ 
		$sql = "select * from Technologies";
		$technologies = DBQuery($sql);	
	}
	else if ($action == 'edit' || $action == 'delete' )
	{ 
		$id = trim(Sanitize($_GET['id']));

		$sql = "select * from Technologies t where t.TechnologyId = $id";
		$technologies = DBQuery($sql);	
		$technology = DBFetch($technologies);
	
		$id = $technology['TechnologyId'];
		$name = $technology['Name'];
		$description = $technology['Description'];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= _($pageName) ?></title>
    </head>
    <body>
        <?php include('includes/header.php'); ?>
        <?php include('includes/mainmenu.php'); ?>
<?php 
	if ($action == 'select') // Selection -----------------------------------
	{ 
?>
        <table>
			<tr>
				<td>
					<a href="?action=new"><img alt="" src="images/new.png" border="0" align="absmiddle"></a>
				</td>
				<td></td>
			</tr>
		<?php
			while($technology = DBFetch($technologies))
			{
		?>
			<tr>
				<td>
					<a href="?action=edit&id=<?= $technology['TechnologyId'] ?>"><img alt="" src="images/edit.png" border="0" align="absmiddle"></a>
					<a href="?action=delete&id=<?= $technology['TechnologyId'] ?>"><img alt="" src="images/delete.png" border="0" align="absmiddle"></a>
				</td>
				<td><?= $technology['Name'] ?></td>
			</tr>
		<? 
			}
		?>
			<tr>
				<td>
					<a href="?action=new"><img alt="" src="images/new.png" border="0" align="absmiddle"></a>
				</td>
				<td></td>
			</tr>
		</table>
<?php 
	} 
	else if ($action == 'new' || $action == 'edit' || $action == 'delete')
	{
?>
        <form action="#" method="post" >
			<input type="hidden" name="id" value="<?= $id ?>" />
            <table>
                <tr>
                    <td>
                          <?= _('Name:') ?>
                    </td>
                    <td>
                        <input type="text" name="name" value="<?= $name ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                          <?= _('Description:') ?>
                    </td>
                    <td>
                        <textarea name="description" rows="15" cols="50"><?= $description ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="<?= $action == 'delete' ? 'delete' : 'save' ?>" value="<?= $action == 'delete' ? _('Delete') : _('Save') ?>" />
                        <input type="submit" name="cancel" value="<?= _('Cancel') ?>" />
                    </td>
                </tr>
            </table>
        </form>
<?php
	}
?>
        <?php include('includes/footer.php'); ?>
    </body>
</html>
